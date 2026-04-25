<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Enrollment;
use App\Models\Setting;
use App\Models\Payment;
use App\Models\EnrollmentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EnrollmentConfirmed;
use Barryvdh\DomPDF\Facade\Pdf;

class EnrollmentController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $enrollments = $student->enrollments()
            ->with(['section.subject', 'section.teacher.user', 'section.room'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        $currentEnrollments = $enrollments->where('status', 'enrolled');
        $historyEnrollments = $enrollments->where('status', '!=', 'enrolled');
        
        return view('enrollments.index', compact('student', 'currentEnrollments', 'historyEnrollments'));
    }
    
    public function enroll(Request $request, Section $section)
    {
        $student = Auth::user()->student;
        $now = now();

        // 1. Enrollment Period check
        $start = Setting::getValue('enrollment_start');
        $end = Setting::getValue('enrollment_end');

        if ($start && $now->lt(\Illuminate\Support\Carbon::parse($start))) {
            return redirect()->back()->with('error', 'Enrollment has not started yet. Opens on: ' . $start);
        }
        if ($end && $now->gt(\Illuminate\Support\Carbon::parse($end))) {
            return redirect()->back()->with('error', 'Enrollment period has already ended.');
        }

        // 2. Semester & School Year check
        $activeSemester = Setting::getValue('active_semester');
        $activeSY = Setting::getValue('active_school_year');

        if ($section->semester != $activeSemester || $section->school_year != $activeSY) {
            return redirect()->back()->with('error', 'This section is not offered in the current active semester/school year.');
        }
        
        if (!$student->isAdmitted()) {
            return redirect()->back()->with('error', 'You cannot enroll yet. Please wait for admission confirmation.');
        }
        
        $existingEnrollment = Enrollment::where('student_id', $student->id)
            ->where('section_id', $section->id)
            ->where('status', 'enrolled')
            ->first();
            
        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this section.');
        }

        // Check if already enrolled in another section of the same subject
        $sameSubjectEnrollment = Enrollment::where('student_id', $student->id)
            ->where('status', 'enrolled')
            ->whereHas('section', function($q) use ($section) {
                $q->where('subject_id', $section->subject_id);
            })->first();

        if ($sameSubjectEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in another section of this subject.');
        }

        // Fetch Student Enrollment Data for current constraints
        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');
        
        $enrollmentData = EnrollmentData::where('student_id', $student->id)
            ->where('semester', $activeSemester)
            ->where('academic_year', $activeSY)
            ->first();

        // 3. Prerequisite check (Conditional)
        $shouldCheckPrereqs = $enrollmentData ? $enrollmentData->check_prerequisites : true;
        
        if ($shouldCheckPrereqs) {
            $prerequisites = $section->subject->prerequisites;
            foreach ($prerequisites as $prereq) {
                $isPassed = Enrollment::where('student_id', $student->id)
                    ->whereHas('section', function($q) use ($prereq) {
                        $q->where('subject_id', $prereq->id);
                    })
                    ->whereHas('grades', function($q) {
                        $q->where('final_grade', '>=', 75);
                    })->exists();

                if (!$isPassed) {
                    return redirect()->back()->with('error', 'Prerequisite not met: ' . $prereq->subject_name . ' (' . $prereq->subject_code . ') must be passed first.');
                }
            }
        }

        // 4. Max Unit check (Dynamic)
        $maxUnits = $enrollmentData ? $enrollmentData->max_units : (int) Setting::getValue('max_units', 24);
        $currentUnits = $student->totalUnits();
        $requestedUnits = $section->subject->units;

        if (($currentUnits + $requestedUnits) > $maxUnits) {
            return redirect()->back()->with('error', "Maximum unit cap reached ($maxUnits units). Current: $currentUnits, Requested: $requestedUnits.");
        }
        
        // Capacity check
        if ($section->enrollments()->where('status', 'enrolled')->count() >= $section->capacity) {
            return redirect()->back()->with('error', 'This section is already full.');
        }
        
        // Schedule conflict check
        $conflicts = $student->enrollments()
            ->with('section')
            ->where('status', 'enrolled')
            ->get()
            ->filter(function($enrolled) use ($section) {
                return $enrolled->section->day === $section->day &&
                       ($enrolled->section->start_time < $section->end_time && $enrolled->section->end_time > $section->start_time);
            });
            
        if ($conflicts->count() > 0) {
            return redirect()->back()->with('error', 'Schedule conflict with: ' . $conflicts->first()->section->subject->subject_name);
        }
        
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'section_id' => $section->id,
            'status' => 'enrolled',
            'enrollment_date' => now(),
        ]);

        // Auto-generate Payment if it doesn't exist for this semester
        Payment::firstOrCreate(
            [
                'student_id' => $student->id,
                'semester' => $activeSemester,
                'school_year' => $activeSY,
                'status' => 'pending'
            ],
            [
                'amount' => $student->totalTuition(),
                'reference_number' => 'BILL-' . strtoupper(Str::random(6)),
            ]
        );

        Mail::to(Auth::user()->email)->send(new EnrollmentConfirmed($enrollment));
        $this->logAction('Enrolled in Section', $section);
        
        return redirect()->back()->with('success', 'Successfully enrolled in ' . $section->subject->subject_name);
    }
    
    public function drop(Section $section)
    {
        $student = Auth::user()->student;
        $now = now();

        // Enrollment/Drop period check
        $start = Setting::getValue('enrollment_start');
        $end = Setting::getValue('enrollment_end');

        if ($start && $now->lt(\Illuminate\Support\Carbon::parse($start))) {
            return redirect()->back()->with('error', 'The add/drop period has not started yet.');
        }
        if ($end && $now->gt(\Illuminate\Support\Carbon::parse($end))) {
            return redirect()->back()->with('error', 'The add/drop period has already ended. Contact the Registrar for assistance.');
        }
        
        $enrollment = Enrollment::where('student_id', $student->id)
            ->where('section_id', $section->id)
            ->where('status', 'enrolled')
            ->first();
            
        if (!$enrollment) {
            return redirect()->back()->with('error', 'Enrollment record not found.');
        }
        
        $enrollment->update(['status' => 'dropped']);
        $this->logAction('Dropped Section', $section);
        
        return redirect()->back()->with('success', 'Successfully dropped ' . $section->subject->subject_name);
    }

    public function viewCor()
    {
        $student = Auth::user()->student;
        $enrollments = $student->enrollments()
            ->with(['section.subject', 'section.room'])
            ->where('status', 'enrolled')
            ->get();
            
        return view('enrollments.cor', compact('student', 'enrollments'));
    }

    public function downloadCor()
    {
        $student = Auth::user()->student;
        $activeSemester = Setting::getValue('active_semester');
        $activeSY = Setting::getValue('active_school_year');

        $enrollments = $student->enrollments()
            ->with(['section.subject', 'section.room'])
            ->where('status', 'enrolled')
            ->whereHas('section', function($q) use ($activeSemester, $activeSY) {
                if ($activeSemester) $q->where('semester', $activeSemester);
                if ($activeSY) $q->where('school_year', $activeSY);
            })
            ->get();

        $pdf = Pdf::loadView('enrollments.pdf_cor', compact('student', 'enrollments', 'activeSemester', 'activeSY'));
        
        $this->logAction('Downloaded COR (PDF)');
        
        return $pdf->download('COR_' . $student->student_number . '.pdf');
    }
}