<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::withCount('subjects');
        
        if ($request->filled('campus')) {
            $query->where('campus', $request->campus);
        }
        
        $courses = $query->orderBy('campus')->orderBy('course_name')->paginate(10)->withQueryString();
        $campuses = Course::select('campus')->whereNotNull('campus')->distinct()->pluck('campus');
        
        return view('admin.courses.index', compact('courses', 'campuses'));
    }
    
    public function create()
    {
        return view('admin.courses.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses',
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        Course::create($request->all());
        
        return redirect()->route('admin.courses.index')->with('success', 'Course created successfully.');
    }
    
    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }
    
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $course->id,
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $course->update($request->all());
        
        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully.');
    }
    
    public function destroy(Course $course)
    {
        if ($course->subjects()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete course with existing subjects.');
        }
        
        $course->delete();
        
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted successfully.');
    }
}