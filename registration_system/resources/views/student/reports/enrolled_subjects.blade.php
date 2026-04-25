@extends('layouts.app')

@section('content')
<div class="section-header">
    <div>
        <h2 class="page-title">Enrolled Subjects</h2>
        <p class="page-subtitle">Academic load for {{ $activeSY }}, Semester {{ $activeSemester }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('enrollments.cor') }}" class="btn-navy">
            <i class="fas fa-file-pdf me-2"></i> Print My COR
        </a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Units</div>
                    <div class="stat-value text-navy">{{ $enrollments->sum(fn($e) => $e->section->subject->units ?? 0) }}</div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-layer-group"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Subjects</div>
                    <div class="stat-value text-navy">{{ $enrollments->count() }}</div>
                </div>
                <div class="stat-icon green">
                    <i class="fas fa-book"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Enrollment Status</div>
                    <div class="stat-value text-navy" style="font-size: 1.2rem;">OFFICIALLY ENROLLED</div>
                </div>
                <div class="stat-icon gold">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="glass-card p-4">
    <div class="card-header-modern mb-4">
        <i class="fas fa-calendar-alt"></i>
        <h3>Weekly Schedule</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Section</th>
                    <th>Day & Time</th>
                    <th>Room</th>
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                <tr>
                    <td>
                        <div class="fw-bold">{{ $enrollment->section->subject->subject_code }}</div>
                        <div class="text-muted small">{{ $enrollment->section->subject->subject_name }}</div>
                        <span class="badge bg-light text-dark">{{ $enrollment->section->subject->units }} Units</span>
                    </td>
                    <td class="fw-bold text-navy">{{ $enrollment->section->name }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock text-gold"></i>
                            <div>
                                <div class="fw-bold small">{{ $enrollment->section->day }}</div>
                                <div class="text-muted small">{{ $enrollment->section->start_time }} - {{ $enrollment->section->end_time }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $enrollment->section->room->name ?? 'TBA' }}</td>
                    <td>{{ $enrollment->section->teacher->user->name ?? 'TBA' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                            <p>No enrolled subjects found for this term.</p>
                            <a href="{{ route('student.transactions.enrollment') }}" class="btn-navy btn-sm">Enroll Now</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
