<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Enrollment;
use App\Models\Attendance;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionManagementController extends Controller
{
    private function checkAccess(Section $section)
    {
        if (Auth::user()->teacher->id !== $section->teacher_id) {
            abort(403, 'Unauthorized access to this section.');
        }
    }

    public function attendance(Request $request, Section $section)
    {
        $this->checkAccess($section);
        
        $date = $request->input('date', date('Y-m-d'));
        
        $enrollments = $section->enrollments()
            ->with(['student.user', 'attendances'])
            ->where('status', 'enrolled')
            ->get();

        // Calculate attendance summary for each student
        foreach ($enrollments as $enrollment) {
            $totalClasses = $enrollment->student->attendances()->where('section_id', $section->id)->count();
            $presentClasses = $enrollment->student->attendances()->where('section_id', $section->id)->where('status', 'Present')->count();
            $enrollment->attendance_percentage = $totalClasses > 0 ? ($presentClasses / $totalClasses) * 100 : 100;
        }
            
        $attendances = Attendance::where('section_id', $section->id)
            ->where('date', $date)
            ->get()
            ->keyBy('student_id');
            
        return view('teacher.sections.attendance', compact('section', 'enrollments', 'date', 'attendances'));
    }

    public function attendanceHistory(Section $section)
    {
        $this->checkAccess($section);

        $attendanceDates = Attendance::where('section_id', $section->id)
            ->select('date')
            ->distinct()
            ->orderBy('date', 'desc')
            ->paginate(15);

        $students = $section->enrollments()
            ->with('student.user')
            ->where('status', 'enrolled')
            ->get();

        // Matrix of attendance: [date][student_id] = status
        $history = [];
        foreach ($attendanceDates as $dateObj) {
            $date = $dateObj->date;
            $records = Attendance::where('section_id', $section->id)
                ->where('date', $date)
                ->get()
                ->keyBy('student_id');
            
            $history[$date] = $records;
        }

        return view('teacher.sections.attendance_history', compact('section', 'students', 'attendanceDates', 'history'));
    }

    public function saveAttendance(Request $request, Section $section)
    {
        $this->checkAccess($section);
        
        $date = $request->input('date', date('Y-m-d'));
        
        foreach ($request->status as $studentId => $status) {
            Attendance::updateOrCreate(
                [
                    'section_id' => $section->id,
                    'student_id' => $studentId,
                    'date' => $date
                ],
                ['status' => $status]
            );
        }
        
        return redirect()->back()->with('success', 'Attendance record saved for ' . $date);
    }

    public function grades(Section $section)
    {
        $this->checkAccess($section);
        
        $enrollments = $section->enrollments()
            ->with(['student.user', 'grades'])
            ->where('status', 'enrolled')
            ->get();
            
        return view('teacher.sections.grades', compact('section', 'enrollments'));
    }

    public function saveGrades(Request $request, Section $section)
    {
        $this->checkAccess($section);
        
        if ($section->grades_published) {
            return redirect()->back()->with('error', 'Grades are already published and cannot be modified.');
        }
        
        foreach ($request->grades as $studentId => $data) {
            Grade::updateOrCreate(
                [
                    'enrollment_id' => $data['enrollment_id'],
                ],
                [
                    'midterm_grade' => $data['midterm'] ?? null,
                    'final_grade'   => $data['final'] ?? null,
                    'remarks'       => $data['remarks'] ?? '',
                ]
            );
        }
        
        return redirect()->back()->with('success', 'Grades updated successfully.');
    }

    public function publishGrades(Request $request, Section $section)
    {
        $this->checkAccess($section);
        
        if ($section->grades_published) {
            return redirect()->back()->with('error', 'Grades are already published.');
        }
        
        $section->grades_published = true;
        $section->save();
        
        return redirect()->back()->with('success', 'Grades have been published successfully.');
    }

    public function downloadRoster(Section $section)
    {
        $this->checkAccess($section);
        
        $enrollments = $section->enrollments()
            ->with('student.user')
            ->where('status', 'enrolled')
            ->orderBy('created_at')
            ->get();
            
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('teacher.sections.roster_pdf', compact('section', 'enrollments'));
        
        return $pdf->download('Class_Roster_' . $section->section_name . '.pdf');
    }

    public function importAttendance(Request $request, Section $section)
    {
        $this->checkAccess($section);

        $request->validate([
            'attendance_csv' => 'required|file|mimes:csv,txt|max:2048',
            'date' => 'required|date'
        ]);

        $file = fopen($request->file('attendance_csv')->getRealPath(), 'r');
        $header = fgetcsv($file);

        // Expecting: student_number, status (Present, Absent, Late)
        $successCount = 0;

        while ($row = fgetcsv($file)) {
            if (count($row) < 2) continue;

            $studentNumber = trim($row[0]);
            $status = ucfirst(strtolower(trim($row[1])));

            if (!in_array($status, ['Present', 'Absent', 'Late', 'Excused'])) {
                $status = 'Absent';
            }

            $enrollment = Enrollment::where('section_id', $section->id)
                ->where('status', 'enrolled')
                ->whereHas('student', function($q) use ($studentNumber) {
                    $q->where('student_number', $studentNumber);
                })->first();

            if ($enrollment) {
                Attendance::updateOrCreate(
                    [
                        'section_id' => $section->id,
                        'student_id' => $enrollment->student_id,
                        'date' => $request->date
                    ],
                    ['status' => $status]
                );
                $successCount++;
            }
        }
        fclose($file);

        return redirect()->back()->with('success', "$successCount attendance records imported successfully for {$request->date}.");
    }

    public function importGrades(Request $request, Section $section)
    {
        $this->checkAccess($section);

        if ($section->grades_published) {
            return redirect()->back()->with('error', 'Grades are already published and cannot be modified.');
        }

        $request->validate([
            'grades_csv' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = fopen($request->file('grades_csv')->getRealPath(), 'r');
        $header = fgetcsv($file);

        // Expecting: student_number, midterm, final, remarks
        $successCount = 0;

        while ($row = fgetcsv($file)) {
            if (count($row) < 2) continue;

            $studentNumber = trim($row[0]);
            $midterm = trim($row[1] ?? '') !== '' ? trim($row[1]) : null;
            $final = trim($row[2] ?? '') !== '' ? trim($row[2]) : null;
            $remarks = trim($row[3] ?? '');

            $enrollment = Enrollment::where('section_id', $section->id)
                ->where('status', 'enrolled')
                ->whereHas('student', function($q) use ($studentNumber) {
                    $q->where('student_number', $studentNumber);
                })->first();

            if ($enrollment) {
                Grade::updateOrCreate(
                    ['enrollment_id' => $enrollment->id],
                    [
                        'midterm_grade' => $midterm,
                        'final_grade'   => $final,
                        'remarks'       => $remarks,
                    ]
                );
                $successCount++;
            }
        }
        fclose($file);

        return redirect()->back()->with('success', "$successCount grade records imported successfully.");
    }

    public function storeAnnouncement(Request $request)
    {
        $teacher = Auth::user()->teacher;

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'priority' => 'required|in:normal,high',
            'section_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($teacher) {
                    if ($value && !Section::where('id', $value)->where('teacher_id', $teacher->id)->exists()) {
                        $fail('The selected section is invalid or you do not have access to it.');
                    }
                },
            ],
        ]);

        \App\Models\Announcement::create([
            'teacher_id' => $teacher->id,
            'section_id' => $request->section_id,
            'title' => $request->title,
            'content' => $request->content,
            'priority' => $request->priority,
        ]);

        return redirect()->back()->with('success', 'Announcement posted successfully.');
    }
}
