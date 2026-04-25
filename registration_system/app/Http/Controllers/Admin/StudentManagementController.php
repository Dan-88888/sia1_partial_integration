<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\EnrollmentData;
use App\Models\Setting;

class StudentManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with(['user', 'latestEnrollmentData']);

        // Search by name, email, or student number
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('student_number', 'like', "%$search%")
                  ->orWhere('course', 'like', "%$search%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        // Filter by course
        if ($request->filled('course')) {
            $query->where('course', $request->course);
        }

        // Filter by campus
        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }

        // Filter by college
        if ($request->filled('college')) {
            $query->where('college', $request->college);
        }

        // Filter by year level
        if ($request->filled('year_level')) {
            $query->where('year_level', $request->year_level);
        }

        $students = $query->orderBy('campus')->orderBy('course')->orderBy('year_level')->orderBy('id', 'desc')->paginate(50)->withQueryString();
        
        $courses = Student::select('course')->distinct()->orderBy('course')->pluck('course');
        $campuses = Student::select('campus')->whereNotNull('campus')->distinct()->orderBy('campus')->pluck('campus');
        $colleges = Student::select('college')->whereNotNull('college')->distinct()->orderBy('college')->pluck('college');

        return view('admin.students.index', compact('students', 'courses', 'campuses', 'colleges'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        $courses = \App\Models\Course::orderBy('course_code')->get();
        return view('admin.students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $student->user_id,
            'course' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:6',
            'admission_status' => 'required|in:admitted,pending,rejected',
        ]);

        \DB::transaction(function () use ($request, $student) {
            $student->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

            $student->update([
                'course' => $request->course,
                'year_level' => $request->year_level,
                'admission_status' => $request->admission_status,
            ]);
        });

        $this->logAction('Updated Student Profile', $student);

        return redirect()->route('admin.students.index')->with('success', 'Student profile updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $user = $student->user;
        $email = $user ? $user->email : null;

        \DB::transaction(function () use ($student, $user, $email) {
            // Delete Student
            $student->delete();
            
            // Delete User
            if ($user) {
                $user->delete();
            }

            // Delete associated application record if it exists
            if ($email) {
                \App\Models\Application::where('email', $email)->delete();
            }
        });

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['message' => 'Student, user account, and application records deleted successfully.']);
        }

        return redirect()->back()->with('success', 'Student and all associated records have been removed.');
    }

    public function enrollmentData(Student $student)
    {
        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        $enrollmentData = EnrollmentData::firstOrCreate([
            'student_id' => $student->id,
            'semester' => $activeSemester,
            'academic_year' => $activeSY
        ]);

        return view('admin.students.enrollment_data', compact('student', 'enrollmentData'));
    }

    public function updateEnrollmentData(Request $request, Student $student)
    {
        $activeSemester = Setting::getValue('active_semester', '1');
        $activeSY = Setting::getValue('active_school_year', '2024-2025');

        $data = $request->validate([
            'year_level' => 'required|integer',
            'status' => 'required|string',
            'max_units' => 'required|integer',
            'check_prerequisites' => 'nullable|boolean',
            'check_enrollment_count' => 'nullable|boolean',
            'payment_plan' => 'nullable|string',
            'course_code' => 'nullable|string',
            'curriculum' => 'nullable|string',
            'section_no' => 'nullable|string',
            'section_name' => 'nullable|string',
            'dept' => 'nullable|string',
            'tf_level' => 'nullable|string',
            'late_enrollee_days' => 'nullable|integer',
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

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');
        
        // Skip header
        fgetcsv($handle);
        
        try {
            \DB::beginTransaction();

            while (($row = fgetcsv($handle)) !== FALSE) {
                if (count($row) < 4) continue; // Skip empty/invalid rows

                $name = trim($row[0]);
                $email = trim($row[1]);
                $course = trim($row[2]);
                $yearLevel = trim($row[3]);

                // Basic validation inside loop to trigger catch
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email format for student: $name ($email)");
                }

                if (\App\Models\User::where('email', $email)->exists()) {
                    throw new \Exception("Duplicate email detected: $email");
                }

                $user = \App\Models\User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => \Hash::make('password123'), // Default password
                    'role' => 'student',
                    'must_change_password' => true,
                ]);

                Student::create([
                    'user_id' => $user->id,
                    'student_number' => 'STU-' . date('Y') . '-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                    'course' => $course,
                    'year_level' => $yearLevel,
                    'admission_status' => 'admitted',
                    'admission_date' => now(),
                ]);
            }

            \DB::commit();
            fclose($handle);
            return redirect()->back()->with('success', 'Bulk student import completed successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Import Failed: ' . $e->getMessage() . '. No data was saved to the database.');
        }
    }
}
