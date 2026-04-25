@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-calendar-plus me-2" style="color:var(--navy);"></i> Scheduling Assignment</h1>
            <p class="page-subtitle">Configure class timings, instructors, and locations for a new section</p>
        </div>
        <a href="{{ route('admin.sections.index') }}" class="btn btn-navy">
            <i class="fas fa-list me-2"></i> View Current Schedules
        </a>
    </div>
</div>

<!-- Main Form Card -->
<div class="glass-card" style="max-width: 900px; margin: 0 auto;" data-aos="fade-up">
    <form action="{{ route('admin.sections.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Section Identifier</label>
                    <input type="text" name="section_name" class="form-modern-input" placeholder="e.g. IT1-A" required>
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-modern-group">
                    <label class="form-modern-label">Target Subject</label>
                    <select name="subject_id" class="form-modern-input" required>
                        <option value="">Select Catalog Subject</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }} ({{ $subject->subject_code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Assigned Instructor</label>
                    <select name="teacher_id" class="form-modern-input" required>
                        <option value="">Select Faculty Member</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Assigned Location</label>
                    <select name="room_id" class="form-modern-input" required>
                        <option value="">Select Facility</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->building }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Lecture Day</label>
                    <select name="day" class="form-modern-input" required>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Start Time</label>
                    <input type="time" name="start_time" class="form-modern-input" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">End Time</label>
                    <input type="time" name="end_time" class="form-modern-input" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Class Capacity</label>
                    <input type="number" name="capacity" class="form-modern-input" value="40" required>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Semester Term</label>
                    <select name="semester" class="form-modern-input" required>
                        <option value="1">1st Semester</option>
                        <option value="2">2nd Semester</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Academic Year</label>
                    <input type="text" name="school_year" class="form-modern-input" value="2024-2025" placeholder="e.g. 2024-2025" required>
                </div>
            </div>
        </div>

        <div class="mt-5 d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.sections.index') }}" class="btn btn-navy" style="background:transparent; color:var(--text-secondary); border:1px solid var(--border);">
                Cancel
            </a>
            <button type="submit" class="btn btn-navy">
                <i class="fas fa-calendar-check me-2"></i> Confirm Schedule
            </button>
        </div>
    </form>
</div>
@endsection
