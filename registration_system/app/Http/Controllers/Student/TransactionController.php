<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Section;
use App\Models\PreEnlistment;
use App\Models\Setting;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\EnrollmentData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function preEnlistment()
    {
        $student = Auth::user()->student;
        if (!$student) abort(404);

        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        // Get subjects already pre-enlisted
        $preEnlistedIds = $student->preEnlistments()
            ->where('semester', $activeSemester)
            ->where('school_year', $activeSY)
            ->pluck('subject_id')
            ->toArray();

        // Get all subjects (simplified: you could filter by curriculum/course)
        $subjects = Subject::orderBy('subject_code')->get();

        $enrollmentData = EnrollmentData::where('student_id', $student->id)
            ->where('semester', $activeSemester)
            ->where('academic_year', $activeSY)
            ->first();

        return view('student.transactions.pre_enlistment', compact('student', 'subjects', 'preEnlistedIds', 'activeSemester', 'activeSY', 'enrollmentData'));
    }

    public function addPreEnlistment(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $student = Auth::user()->student;
        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        $subject = Subject::findOrFail($request->subject_id);

        // 1. Prerequisite check
        $prerequisites = $subject->prerequisites;
        foreach ($prerequisites as $prereq) {
            $isPassed = Enrollment::where('student_id', $student->id)
                ->whereHas('section', function($q) use ($prereq) {
                    $q->where('subject_id', $prereq->id);
                })
                ->whereHas('grades', function($q) {
                    $q->where('final_grade', '>=', 75);
                })->exists();

            if (!$isPassed) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Prerequisite not met: ' . $prereq->subject_name . ' must be passed first.'
                ]);
            }
        }

        // 2. Max Unit check
        $currentPreEnlistedUnits = $student->preEnlistments()
            ->where('semester', $activeSemester)
            ->where('school_year', $activeSY)
            ->with('subject')
            ->get()
            ->sum(function($p) {
                return $p->subject->units ?? 0;
            });
            
        $enrollmentData = EnrollmentData::where('student_id', $student->id)
            ->where('semester', $activeSemester)
            ->where('academic_year', $activeSY)
            ->first();

        $maxUnits = $enrollmentData ? $enrollmentData->max_units : (int) Setting::getValue('max_units', 24);
        
        if (($currentPreEnlistedUnits + $subject->units) > $maxUnits) {
            return response()->json([
                'success' => false, 
                'message' => "Maximum unit cap reached ($maxUnits units). Current: $currentPreEnlistedUnits."
            ]);
        }

        PreEnlistment::firstOrCreate([
            'student_id' => $student->id,
            'subject_id' => $request->subject_id,
            'semester' => $activeSemester,
            'school_year' => $activeSY
        ]);

        return response()->json(['success' => true]);
    }

    public function removePreEnlistment(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id'
        ]);

        $student = Auth::user()->student;
        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        PreEnlistment::where('student_id', $student->id)
            ->where('subject_id', $request->subject_id)
            ->where('semester', $activeSemester)
            ->where('school_year', $activeSY)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function updateEnrollmentData(Request $request)
    {
        $student = Auth::user()->student;
        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        $data = $request->validate([
            'year_level' => 'required|integer|min:1|max:5',
            'status' => 'required|string',
            'payment_plan' => 'nullable|string',
            'course_code' => 'nullable|string',
            'curriculum' => 'nullable|string',
            'section_no' => 'nullable|string',
            'section_name' => 'nullable|string',
            'dept' => 'nullable|string',
            'tf_level' => 'nullable|string',
            'max_units' => 'required|integer|min:1|max:50',
            'late_enrollee_days' => 'nullable|integer',
            'check_prerequisites' => 'nullable|boolean',
            'check_enrollment_count' => 'nullable|boolean',
        ]);

        $data['check_prerequisites'] = $request->has('check_prerequisites');
        $data['check_enrollment_count'] = $request->has('check_enrollment_count');

        EnrollmentData::updateOrCreate(
            [
                'student_id' => $student->id,
                'semester' => $activeSemester,
                'academic_year' => $activeSY
            ],
            $data
        );

        return redirect()->back()->with('success', 'Enrollment data updated successfully.');
    }

    public function enrollment()
    {
        $student = Auth::user()->student;
        if (!$student) abort(404);

        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        // Get pre-enlisted subjects
        $preEnlistedSubjects = $student->preEnlistments()
            ->with('subject')
            ->where('semester', $activeSemester)
            ->where('school_year', $activeSY)
            ->get()
            ->pluck('subject');

        // If no pre-enlisted subjects, allow choosing from all offered subjects (flexible requirement)
        $offeredSubjects = Subject::whereHas('sections', function($q) use ($activeSemester, $activeSY) {
            $q->where('semester', $activeSemester)->where('school_year', $activeSY);
        })->get();

        // Get sections for all offered subjects to allow flexible enrollment
        $sections = Section::with(['subject', 'teacher.user', 'room'])
            ->where('semester', $activeSemester)
            ->where('school_year', $activeSY)
            ->get()
            ->groupBy('subject_id');

        // Current enrollments to prevent double enrollment
        $currentEnrollmentIds = $student->enrollments()
            ->where('status', 'enrolled')
            ->pluck('section_id')
            ->toArray();

        return view('student.transactions.enrollment', compact(
            'student', 'preEnlistedSubjects', 'offeredSubjects', 'sections', 
            'currentEnrollmentIds', 'activeSemester', 'activeSY'
        ));
    }
}
