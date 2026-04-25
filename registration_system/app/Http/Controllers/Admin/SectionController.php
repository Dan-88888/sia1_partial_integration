<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Room;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index()
    {
        $sections = Section::with(['subject', 'teacher', 'room'])->paginate(15);
        return view('admin.sections.index', compact('sections'));
    }

    public function create()
    {
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();
        $rooms = Room::all();
        return view('admin.sections.create', compact('subjects', 'teachers', 'rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'required|exists:rooms,id',
            'section_name' => 'required|string|max:50',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|integer|min:1',
            'semester' => 'required|integer',
            'school_year' => 'required|string',
        ]);

        // Schedule conflict detection
        $conflict = $this->detectConflicts($request);
        if ($conflict) {
            return redirect()->back()->withInput()->with('error', $conflict);
        }
        
        Section::create($request->all());
        return redirect()->route('admin.sections.index')->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $subjects = Subject::all();
        $teachers = Teacher::with('user')->get();
        $rooms = Room::all();
        return view('admin.sections.edit', compact('section', 'subjects', 'teachers', 'rooms'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'room_id' => 'required|exists:rooms,id',
            'section_name' => 'required|string|max:50',
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'capacity' => 'required|integer|min:1',
            'semester' => 'required|integer',
            'school_year' => 'required|string',
        ]);

        // Schedule conflict detection (exclude current section)
        $conflict = $this->detectConflicts($request, $section->id);
        if ($conflict) {
            return redirect()->back()->withInput()->with('error', $conflict);
        }

        $section->update($request->all());
        return redirect()->route('admin.sections.index')->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        if ($section->enrollments()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete section with active enrollments.');
        }

        $section->delete();
        return redirect()->route('admin.sections.index')->with('success', 'Section deleted successfully.');
    }

    /**
     * Check for teacher or room schedule conflicts.
     * Returns error message string if conflict found, null if clear.
     */
    private function detectConflicts(Request $request, $excludeId = null)
    {
        $query = Section::where('day', $request->day)
            ->where('semester', $request->semester)
            ->where('school_year', $request->school_year)
            ->where('start_time', '<', $request->end_time)
            ->where('end_time', '>', $request->start_time);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        // Check teacher conflict
        $teacherConflict = (clone $query)->where('teacher_id', $request->teacher_id)->with('subject')->first();
        if ($teacherConflict) {
            $teacher = Teacher::with('user')->find($request->teacher_id);
            return "Schedule conflict: {$teacher->user->name} is already assigned to \"{$teacherConflict->subject->subject_name} ({$teacherConflict->section_name})\" on {$teacherConflict->day} from {$teacherConflict->start_time} to {$teacherConflict->end_time}.";
        }

        // Check room conflict
        $roomConflict = (clone $query)->where('room_id', $request->room_id)->with('subject')->first();
        if ($roomConflict) {
            $room = Room::find($request->room_id);
            return "Schedule conflict: Room \"{$room->name}\" is already booked for \"{$roomConflict->subject->subject_name} ({$roomConflict->section_name})\" on {$roomConflict->day} from {$roomConflict->start_time} to {$roomConflict->end_time}.";
        }

        return null;
    }
}
