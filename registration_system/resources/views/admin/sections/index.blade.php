@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-calendar-alt me-2" style="color:var(--navy);"></i> Class Sections</h1>
            <p class="page-subtitle">Manage academic schedules, teacher assignments, and room allocations</p>
        </div>
        <a href="{{ route('admin.sections.create') }}" class="btn btn-navy">
            <i class="fas fa-plus me-2"></i> Assign New Section
        </a>
    </div>
</div>

<!-- Main Table Card -->
<div class="glass-card" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Section Name</th>
                    <th>Subject</th>
                    <th>Assigned Faculty</th>
                    <th>Location</th>
                    <th>Schedule Details</th>
                    <th>Term/SY</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                <tr>
                    <td class="fw-bold">{{ $section->section_name }}</td>
                    <td>{{ $section->subject->subject_name }}</td>
                    <td><span class="text-navy fw-600">{{ $section->teacher->user->name ?? 'Unassigned' }}</span></td>
                    <td>{{ $section->room->name ?? 'TBA' }}</td>
                    <td>
                        <div style="font-size:0.8rem; color:var(--text-secondary);">
                            <i class="far fa-calendar-check me-1"></i> {{ $section->day }}<br>
                            <i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($section->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($section->end_time)->format('h:i A') }}
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $section->semester }} sem</span><br>
                        <small class="text-muted">{{ $section->school_year }}</small>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.sections.edit', $section) }}" class="btn btn-navy btn-sm" title="Edit Section">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" title="Delete Section">
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
    
    <div class="mt-4">
        {{ $sections->links() }}
    </div>
</div>
@endsection
