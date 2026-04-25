@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">Term Enrollment</h2>
        <p class="page-subtitle">Finalize your schedule for {{ $activeSY }}, Semester {{ $activeSemester }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('student.finance') }}" class="btn-navy" style="background: #28a745;">
            <i class="fas fa-calculator me-2"></i> Assess Enrollment
        </a>
        <a href="{{ route('student.transactions.pre_enlistment') }}" class="btn-outline-gold">
            <i class="fas fa-edit me-2"></i> Edit Pre-Enlistment
        </a>
    </div>
</div>

@if($preEnlistedSubjects->count() > 0)
<div class="glass-card p-4 mb-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header-modern">
        <i class="fas fa-star" style="color: var(--gold);"></i>
        <h3>Pre-Enlisted Subjects</h3>
    </div>
    <p class="text-muted small mb-4">You selected these subjects during pre-enlistment. Choose a section for each to enroll.</p>
    
    <div class="row g-4">
        @foreach($preEnlistedSubjects as $subject)
        <div class="col-md-6 col-xl-4">
            <div class="stat-card p-3">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="fw-bold text-navy" style="font-size: 1.1rem;">{{ $subject->subject_code }}</div>
                        <div class="text-muted small">{{ $subject->subject_name }}</div>
                    </div>
                    <span class="status-badge info">{{ $subject->units }} Units</span>
                </div>
                
                <div class="sections-list">
                    @if(isset($sections[$subject->id]))
                        @foreach($sections[$subject->id] as $section)
                            <div class="section-item p-2 mb-2 rounded border @if(in_array($section->id, $currentEnrollmentIds)) bg-success-subtle border-success @else border-light-subtle @endif">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold small">{{ $section->name }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            <i class="fas fa-clock"></i> {{ $section->day }} {{ $section->start_time }} - {{ $section->end_time }}<br>
                                            <i class="fas fa-user-tie"></i> {{ $section->teacher->user->name ?? 'TBA' }}
                                        </div>
                                    </div>
                                    @if(in_array($section->id, $currentEnrollmentIds))
                                        <span class="text-success fw-bold small"><i class="fas fa-check-circle"></i> Enrolled</span>
                                    @else
                                        <form action="{{ route('enrollments.enroll', $section->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-navy btn-sm" style="padding: 4px 12px; font-size: 0.8rem;">Enroll</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-danger small"><i class="fas fa-exclamation-triangle"></i> No sections offered for this term.</div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif

<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="200">
    <div class="card-header-modern">
        <i class="fas fa-list"></i>
        <h3>Other Offered Subjects</h3>
    </div>
    <p class="text-muted small mb-4">View other subjects available for enrollment this term.</p>

    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Available Sections</th>
                </tr>
            </thead>
            <tbody>
                @foreach($offeredSubjects as $subject)
                    @if(!$preEnlistedSubjects->contains('id', $subject->id))
                    <tr>
                        <td style="width: 300px;">
                            <div class="fw-bold">{{ $subject->subject_code }}</div>
                            <div class="text-muted small">{{ $subject->subject_name }}</div>
                            <span class="badge bg-light text-dark">{{ $subject->units }} Units</span>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-2">
                                @if(isset($sections[$subject->id]))
                                    @foreach($sections[$subject->id] as $section)
                                        <div class="p-2 border rounded border-light-subtle" style="min-width: 200px;">
                                            <div class="d-flex justify-content-between align-items-center gap-2">
                                                <div class="small">
                                                    <strong>{{ $section->name }}</strong><br>
                                                    {{ $section->day }} | {{ $section->start_time }}
                                                </div>
                                                @if(in_array($section->id, $currentEnrollmentIds))
                                                    <span class="text-success fw-bold small"><i class="fas fa-check-circle"></i> Enrolled</span>
                                                @else
                                                    <form action="{{ route('enrollments.enroll', $section->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn-navy btn-sm py-1">Enroll</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <span class="text-muted small italic">No sections available</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="glass-card p-4 sticky-bottom mt-4 shadow-lg border-primary border-top" style="z-index: 1000; bottom: 20px;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
        <div class="d-flex gap-4">
            <div class="text-center p-2 rounded bg-white shadow-sm" style="min-width: 120px;">
                <div class="text-muted small fw-bold text-uppercase">Total Subjects</div>
                <div class="h4 mb-0 fw-bold text-navy">{{ count($currentEnrollmentIds) }}</div>
            </div>
            <div class="text-center p-2 rounded bg-white shadow-sm" style="min-width: 120px;">
                <div class="text-muted small fw-bold text-uppercase">Total Units</div>
                @php
                    $totalUnits = $student->enrollments()
                        ->where('status', 'enrolled')
                        ->whereHas('section', function($q) use ($activeSemester, $activeSY) {
                            $q->where('semester', $activeSemester)->where('school_year', $activeSY);
                        })
                        ->get()
                        ->sum(function($e) { return $e->section->subject->units; });
                @endphp
                <div class="h4 mb-0 fw-bold text-primary">{{ $totalUnits }}</div>
            </div>
        </div>
        
        <div class="d-flex gap-2 align-items-center">
            <p class="text-muted small mb-0 d-none d-md-block" style="max-width: 300px;">
                Ensure all subjects are correctly selected before clicking <strong>Assess</strong> to generate your statement of account.
            </p>
            <a href="{{ route('student.finance') }}" class="btn-navy py-3 px-5 hvr-grow" style="background: #28a745; border: none; border-radius: 12px; font-weight: 800; font-size: 1.1rem; box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);">
                <i class="fas fa-check-double me-2"></i> ASSESS ENROLLMENT
            </a>
        </div>
    </div>
</div>
@endsection
