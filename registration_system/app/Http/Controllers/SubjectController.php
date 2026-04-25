<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subject;
use App\Models\Course;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $activeSemester = Setting::getValue('active_semester', 1);
        $activeSY = Setting::getValue('active_school_year', date('Y') . '-' . (date('Y') + 1));

        $query = Section::with(['subject', 'course', 'teacher.user', 'room'])
            ->where('semester', $activeSemester)
            ->where('school_year', $activeSY);
        
        // Search by subject name or code
        if ($request->filled('search')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('subject_name', 'like', '%' . $request->search . '%')
                  ->orWhere('subject_code', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->filled('course')) {
            $query->whereHas('subject', function($q) use ($request) {
                $q->where('course_id', $request->course);
            });
        }
        
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }
        
        $sections = $query->paginate(15);
        $courses = Course::all();
        
        $enrolledSectionIds = [];
        if (Auth::user()->student) {
            $enrolledSectionIds = Auth::user()->student->enrollments()
                ->where('status', 'enrolled')
                ->pluck('section_id')
                ->toArray();
        }
        
        return view('subjects.index', compact('sections', 'courses', 'enrolledSectionIds'));
    }
    
    public function show(Subject $subject)
    {
        $currentEnrollment = $subject->enrollments()->where('status', 'enrolled')->count();
        $availableSlots = $subject->capacity - $currentEnrollment;
        
        return view('subjects.show', compact('subject', 'currentEnrollment', 'availableSlots'));
    }
}