<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdmissionWebhookController extends Controller
{
    /**
     * Receive admitted student data from the Admission System.
     * 
     * Expected payload:
     * {
     *   "student_number": "2026-00001",
     *   "name": "Juan Dela Cruz",
     *   "email": "juan@university.edu",
     *   "course": "BSIT",
     *   "year_level": 1,
     *   "admission_reference": "ADM-2026-0001",
     *   "academic_year": "2026-2027",
     *   "semester": 1
     * }
     */
    public function receive(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_number' => 'required|string|unique:students,student_number',
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'course'         => 'required|string|max:255',
            'year_level'     => 'required|integer|min:1|max:6',
            'admission_reference' => 'required|string|unique:students,admission_reference',
            'academic_year'  => 'nullable|string',
            'semester'       => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            $result = DB::transaction(function () use ($request) {
                // Create or find an existing user
                $user = User::firstOrCreate(
                    ['email' => $request->email],
                    [
                        'name'     => $request->name,
                        'password' => Hash::make('changeme123'), // Temporary password
                        'role'     => 'student',
                    ]
                );

                // Create the student record with admitted status
                $student = Student::create([
                    'user_id'             => $user->id,
                    'student_number'      => $request->student_number,
                    'course'              => $request->course,
                    'year_level'          => $request->year_level,
                    'admission_status'    => 'admitted',
                    'admission_date'      => now(),
                    'admission_reference' => $request->admission_reference,
                ]);

                return [
                    'user'    => $user,
                    'student' => $student,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Student admitted successfully',
                'data'    => [
                    'student_number'      => $result['student']->student_number,
                    'email'               => $result['user']->email,
                    'admission_status'    => $result['student']->admission_status,
                    'temporary_password'  => 'changeme123',
                ],
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process admission',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check admission status for a given student.
     */
    public function status(Request $request)
    {
        $student = Student::where('student_number', $request->student_number)
            ->orWhere('admission_reference', $request->admission_reference)
            ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'student_number'   => $student->student_number,
                'admission_status' => $student->admission_status,
                'admission_date'   => $student->admission_date,
                'course'           => $student->course,
                'year_level'       => $student->year_level,
            ],
        ]);
    }
}
