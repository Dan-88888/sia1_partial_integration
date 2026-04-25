<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\Subject;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(SettingSeeder::class);
        
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@university.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);
        
        // Create teacher user
        $teacherUser = User::create([
            'name' => 'Prof. Maria Santos',
            'email' => 'teacher@university.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
        ]);
        
        // Create teacher profile
        $teacher = \App\Models\Teacher::create([
            'user_id' => $teacherUser->id,
            'teacher_id' => 'TCH-2024-001',
            'department_id' => 1,
        ]);
        
        // Create student user
        $studentUser = User::create([
            'name' => 'John Doe',
            'email' => 'student@university.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);
        
        // Create student profile
        Student::create([
            'user_id' => $studentUser->id,
            'student_number' => '2024-00001',
            'course' => 'Computer Science',
            'year_level' => 2,
            'admission_status' => 'admitted',
            'admission_date' => now(),
        ]);
        
        // Create rooms
        $room101 = \App\Models\Room::create(['name' => 'Room 101', 'capacity' => 40]);
        $room102 = \App\Models\Room::create(['name' => 'Room 102', 'capacity' => 35]);
        
        // Create courses
        $courses = [
            ['course_name' => 'Computer Science', 'course_code' => 'BSCS', 'description' => 'Bachelor of Science in Computer Science'],
            ['course_name' => 'Information Technology', 'course_code' => 'BSIT', 'description' => 'Bachelor of Science in Information Technology'],
        ];
        
        foreach ($courses as $course) {
            \App\Models\Course::create($course);
        }
        
        $csCourse = \App\Models\Course::where('course_code', 'BSCS')->first();
        
        // Create subjects (Metadata only)
        $subjects = [
            ['course_id' => $csCourse->id, 'subject_code' => 'CS101', 'subject_name' => 'Intro to Programming', 'units' => 3],
            ['course_id' => $csCourse->id, 'subject_code' => 'CS102', 'subject_name' => 'Data Structures', 'units' => 3],
        ];
        
        foreach ($subjects as $sData) {
            $subject = \App\Models\Subject::create($sData);
            
            // Create a section for this subject
            \App\Models\Section::create([
                'subject_id' => $subject->id,
                'teacher_id' => $teacher->id,
                'room_id' => $room101->id,
                'section_name' => $subject->subject_code . '-A',
                'day' => 'Monday',
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'capacity' => 40,
                'semester' => 1,
                'school_year' => '2024-2025',
            ]);
        }
    }
}