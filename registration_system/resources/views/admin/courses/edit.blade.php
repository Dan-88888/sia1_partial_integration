@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-graduation-cap me-2" style="color:var(--navy);"></i> Edit Course Program</h1>
            <p class="page-subtitle">Update program details for {{ $course->course_code }}</p>
        </div>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-navy">
            <i class="fas fa-arrow-left me-2"></i> Back to Courses
        </a>
    </div>
</div>

<!-- Main Form Card -->
<div class="glass-card" style="max-width: 800px; margin: 0 auto;" data-aos="fade-up">
    <form action="{{ route('admin.courses.update', $course) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <div class="col-md-4">
                <div class="form-modern-group">
                    <label class="form-modern-label">Course Code</label>
                    <input type="text" name="course_code" class="form-modern-input" value="{{ $course->course_code }}" required>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="form-modern-group">
                    <label class="form-modern-label">Full Course Title</label>
                    <input type="text" name="course_name" class="form-modern-input" value="{{ $course->course_name }}" required>
                </div>
            </div>

            <div class="col-12">
                <div class="form-modern-group">
                    <label class="form-modern-label">Academic Department</label>
                    <input type="text" name="department" class="form-modern-input" value="{{ $course->department }}" placeholder="e.g. College of Engineering and Computational Science (CEC)">
                </div>
            </div>

            <div class="col-12">
                <div class="form-modern-group">
                    <label class="form-modern-label">Program Description</label>
                    <textarea name="description" class="form-modern-input" rows="3">{{ $course->description }}</textarea>
                </div>
            </div>
        </div>

        <div class="mt-5 d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.courses.index') }}" class="btn btn-navy" style="background:transparent; color:var(--text-secondary); border:1px solid var(--border);">
                Cancel
            </a>
            <button type="submit" class="btn btn-navy">
                <i class="fas fa-save me-2"></i> Update Program Details
            </button>
        </div>
    </form>
</div>
@endsection
