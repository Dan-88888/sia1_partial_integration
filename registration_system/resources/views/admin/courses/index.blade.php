@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down" style="margin-bottom: 1.5rem;">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-graduation-cap me-2" style="color:var(--navy);"></i> Course Catalog</h1>
            <p class="page-subtitle">Manage degree programs and campus distributions</p>
        </div>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-navy btn-sm px-4">
            <i class="fas fa-plus me-1"></i> Add Course
        </a>
    </div>
</div>

<!-- Filters -->
<div class="glass-card mb-4" style="padding: 1rem 1.5rem;" data-aos="fade-up">
    <form method="GET" action="{{ route('admin.courses.index') }}" class="row g-2 align-items-end">
        <div class="col-md-4">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Filter by Campus</label>
            <select name="campus" class="form-modern-input py-1" style="font-size: 0.9rem;">
                <option value="">All Campuses</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>{{ $campus }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-navy btn-sm w-100 py-1">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>
        @if(request('campus'))
        <div class="col-md-1">
            <a href="{{ route('admin.courses.index') }}" class="btn btn-sm border text-secondary w-100 py-1" title="Clear">
                <i class="fas fa-times"></i>
            </a>
        </div>
        @endif
    </form>
</div>

<!-- Main Table Card -->
<div class="glass-card mb-5" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 120px;">Code</th>
                    <th>Course Name</th>
                    <th>Campus</th>
                    <th>Department/College</th>
                    <th>Subjects</th>
                    <th class="text-end" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses as $course)
                <tr>
                    <td class="fw-bold text-navy">{{ $course->course_code }}</td>
                    <td style="max-width: 300px;">{{ $course->course_name }}</td>
                    <td><span class="badge bg-light text-primary border">{{ $course->campus ?? 'N/A' }}</span></td>
                    <td><span class="text-muted small">{{ $course->department ?? 'Unassigned' }}</span></td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $course->subjects_count }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="btn btn-navy btn-sm" style="padding: 4px 8px; font-size: 0.8rem;" title="Edit Course">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" style="padding: 4px 8px; font-size: 0.8rem;" title="Delete Course">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($courses->hasPages())
    <div class="px-4 py-3 border-top bg-light/30">
        {{ $courses->links() }}
    </div>
    @endif
</div>
@endsection
