<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('course')->paginate(10);
        return view('admin.subjects.index', compact('subjects'));
    }
    
    public function create()
    {
        $courses = Course::all();
        return view('admin.subjects.create', compact('courses'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_code' => 'required|string|max:50|unique:subjects',
            'subject_name' => 'required|string|max:255',
            'units' => 'required|integer|min:1|max:6',
            'description' => 'nullable|string',
        ]);
        
        Subject::create($request->all());
        
        return redirect()->route('admin.subjects.index')->with('success', 'Subject created successfully.');
    }
    
    public function edit(Subject $subject)
    {
        $courses = Course::all();
        return view('admin.subjects.edit', compact('subject', 'courses'));
    }
    
    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code,' . $subject->id,
            'subject_name' => 'required|string|max:255',
            'units' => 'required|integer|min:1|max:6',
            'description' => 'nullable|string',
        ]);
        
        $subject->update($request->all());
        
        return redirect()->route('admin.subjects.index')->with('success', 'Subject updated successfully.');
    }
    
    public function destroy(Subject $subject)
    {
        if ($subject->enrollments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete subject with existing enrollments.');
        }
        
        $subject->delete();
        
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted successfully.');
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
                if (count($row) < 4) continue;

                $courseCode = trim($row[0]);
                $subjectCode = trim($row[1]);
                $subjectName = trim($row[2]);
                $units = trim($row[3]);

                $course = Course::where('course_code', $courseCode)->first();
                if (!$course) {
                    throw new \Exception("Course code '$courseCode' not found for subject: $subjectName");
                }

                if (Subject::where('subject_code', $subjectCode)->exists()) {
                    throw new \Exception("Duplicate subject code detected: $subjectCode");
                }

                Subject::create([
                    'course_id' => $course->id,
                    'subject_code' => $subjectCode,
                    'subject_name' => $subjectName,
                    'units' => $units,
                ]);
            }

            \DB::commit();
            fclose($handle);
            return redirect()->back()->with('success', 'Bulk subject import completed successfully.');

        } catch (\Exception $e) {
            \DB::rollBack();
            fclose($handle);
            return redirect()->back()->with('error', 'Import Failed: ' . $e->getMessage() . '. No data was saved to the database.');
        }
    }
}