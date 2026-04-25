@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title">
            <i class="fas fa-hand-sparkles me-2" style="color:var(--gold);"></i>
            Welcome, {{ $student->user->name }}!
        </h1>
        <p class="page-subtitle">Here's your academic overview for this semester</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-navy" data-bs-toggle="modal" data-bs-target="#enrollmentDataModal">
            <i class="fas fa-file-invoice me-2"></i> Enrollment Data
        </button>
    </div>
</div>

<!-- Banner removed as per user request (test account) -->

<!-- Stat Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon gold"><i class="fas fa-id-card"></i></div>
                <div>
                    <div class="stat-label">Student ID</div>
                    <div class="stat-value" style="font-size:1rem;font-weight:700;">{{ $student->student_number }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="stat-label">Enrolled Subjects</div>
                    <div class="stat-value">{{ $totalSubjects }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon green"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="stat-label">Total Units</div>
                    <div class="stat-value">{{ $totalUnits }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon purple"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-label">Status</div>
                    <div>
                        <span class="status-badge success">
                            <i class="fas fa-circle" style="font-size:5px;"></i>
                            Admitted
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Student Information -->
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card h-100">
            <div class="card-header-modern">
                <i class="fas fa-user-circle"></i>
                <h4>Student Information</h4>
            </div>
            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:var(--text-muted);font-size:0.85rem;">Full Name</span>
                    <span style="font-weight:600;font-size:0.9rem;">{{ $student->user->name }}</span>
                </div>
                <div style="border-bottom:1px solid var(--glass-border);"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:var(--text-muted);font-size:0.85rem;">Email</span>
                    <span style="font-weight:600;font-size:0.9rem;">{{ $student->user->email }}</span>
                </div>
                <div style="border-bottom:1px solid var(--glass-border);"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:var(--text-muted);font-size:0.85rem;">Course</span>
                    <span style="font-weight:600;font-size:0.9rem;">{{ $student->course ?? 'Not set' }}</span>
                </div>
                <div style="border-bottom:1px solid var(--glass-border);"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:var(--text-muted);font-size:0.85rem;">Year Level</span>
                    <span style="font-weight:600;font-size:0.9rem;">{{ $student->year_level ?? 'Not set' }}</span>
                </div>
                <div style="border-bottom:1px solid var(--glass-border);"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:var(--text-muted);font-size:0.85rem;">Status</span>
                    <span class="status-badge success">
                        Admitted
                    </span>
                </div>
                @if($student->admission_date)
                <div style="border-bottom:1px solid var(--glass-border);"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span style="color:var(--text-muted);font-size:0.85rem;">Admitted On</span>
                    <span style="font-weight:600;font-size:0.9rem;">{{ $student->admission_date->format('M d, Y') }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card h-100">
            <div class="card-header-modern">
                <i class="fas fa-calendar-alt"></i>
                <h4>Weekly Schedule</h4>
            </div>
            <div class="table-responsive">
                <table class="table-modern w-100">
                    <thead>
                        <tr>
                            <th>Day</th>
                            <th>Subject</th>
                            <th>Time</th>
                            <th>Room</th>
                            <th>Instructor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedule as $subject)
                            <tr>
                                <td>
                                    <span class="status-badge info">{{ $subject->day }}</span>
                                </td>
                                <td style="color:var(--text-primary);font-weight:500;">
                                    {{ $subject->subject_name }}
                                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $subject->subject_code }}</div>
                                </td>
                                <td>{{ date('h:i A', strtotime($subject->start_time)) }} - {{ date('h:i A', strtotime($subject->end_time)) }}</td>
                                <td><i class="fas fa-door-open me-1" style="color:var(--gold);font-size:0.75rem;"></i>{{ $subject->room }}</td>
                                <td>{{ $subject->instructor ?? 'TBA' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center" style="padding:2rem;color:var(--text-muted);">
                                    <i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
                                    No subjects enrolled yet.
                                    @if($student->isAdmitted())
                                        <div class="mt-3">
                                            <a href="{{ route('subjects.index') }}" class="btn-gold">
                                                <i class="fas fa-search me-2"></i> Browse Offered Subjects
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row g-3 mt-3" data-aos="fade-up" data-aos-delay="300">
    <div class="col-md-4">
        <a href="{{ route('subjects.index') }}" class="glass-card d-flex align-items-center gap-3 text-decoration-none h-100 hvr-grow" style="padding:1.25rem;">
            <div style="width:44px;height:44px;border-radius:12px;background:rgba(0,207,232,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-book" style="color:#00cfe8;"></i>
            </div>
            <div>
                <div style="font-weight:700;color:var(--text-primary);font-size:0.95rem;">Browse Subjects</div>
                <div style="font-size:0.8rem;color:var(--text-muted);">Find and enroll in new subjects</div>
            </div>
            <i class="fas fa-chevron-right ms-auto text-gold" style="font-size:0.8rem;"></i>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('enrollments.index') }}" class="glass-card d-flex align-items-center gap-3 text-decoration-none h-100 hvr-grow" style="padding:1.25rem;">
            <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,215,0,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-clipboard-list" style="color:var(--gold);"></i>
            </div>
            <div>
                <div style="font-weight:700;color:var(--text-primary);font-size:0.95rem;">My Enrollments</div>
                <div style="font-size:0.8rem;color:var(--text-muted);">View and manage enrolled subjects</div>
            </div>
            <i class="fas fa-chevron-right ms-auto text-gold" style="font-size:0.8rem;"></i>
        </a>
    </div>
    <div class="col-md-4">
        @if(Route::has('certificate.index'))
        <a href="{{ route('certificate.index') }}" class="glass-card d-flex align-items-center gap-3 text-decoration-none h-100 hvr-grow" style="padding:1.25rem;">
            <div style="width:44px;height:44px;border-radius:12px;background:rgba(40,199,111,0.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-certificate" style="color:#28c76f;"></i>
            </div>
            <div>
                <div style="font-weight:700;color:var(--text-primary);font-size:0.95rem;">Certificate</div>
                <div style="font-size:0.8rem;color:var(--text-muted);">View & print registration certificate</div>
            </div>
            <i class="fas fa-chevron-right ms-auto text-gold" style="font-size:0.8rem;"></i>
        </a>
        @endif
    </div>
</div>
@push('modals')
@include('student.partials.enrollment_data_modal')
@endpush
@endsection