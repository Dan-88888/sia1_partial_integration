@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-book-open me-2" style="color:var(--navy);"></i> Add New Subject</h1>
            <p class="page-subtitle">Register a new academic requirement into the university catalog</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="btn btn-navy">
            <i class="fas fa-arrow-left me-2"></i> Back to Catalog
        </a>
    </div>
</div>

<!-- Main Form Card -->
<div class="glass-card" style="max-width: 800px; margin: 0 auto;" data-aos="fade-up">
    <form action="{{ route('admin.subjects.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Linked Course</label>
                    <select name="course_id" class="form-modern-input" required>
                        <option value="">Select Target Course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->course_name }} ({{ $course->course_code }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Subject Code</label>
                    <input type="text" name="subject_code" class="form-modern-input" placeholder="e.g. IT101" required>
                </div>
            </div>

            <div class="col-12">
                <div class="form-modern-group">
                    <label class="form-modern-label">Subject Name</label>
                    <input type="text" name="subject_name" class="form-modern-input" placeholder="Enter formal subject title" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Unit Value</label>
                    <input type="number" name="units" class="form-modern-input" value="3" min="1" max="6" required>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Subject Description</label>
                    <textarea name="description" class="form-modern-input" rows="1" placeholder="Optional brief overview"></textarea>
                </div>
            </div>
        </div>

        <div class="mt-5 d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.subjects.index') }}" class="btn btn-navy" style="background:transparent; color:var(--text-secondary); border:1px solid var(--border);">
                Cancel
            </a>
            <button type="submit" class="btn btn-navy">
                <i class="fas fa-save me-2"></i> Save Subject Record
            </button>
        </div>
    </form>
</div>
@endsection
