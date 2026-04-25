@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-clipboard-list me-2" style="color:var(--gold);"></i> My Enrollments</h1>
        <p class="page-subtitle">Manage your current and past class sections</p>
    </div>
    <div style="display:flex; gap:10px;">
        <a href="{{ route('enrollments.cor.download') }}" class="btn btn-outline-gold btn-sm">
            <i class="fas fa-file-pdf me-1"></i> Download COR (PDF)
        </a>
        <a href="{{ route('enrollments.cor') }}" class="btn btn-outline-gold btn-sm">
            <i class="fas fa-eye me-1"></i> View COR
        </a>
        <a href="{{ route('subjects.index') }}" class="btn btn-gold btn-sm">
            <i class="fas fa-plus me-1"></i> Enroll New Section
        </a>
    </div>
</div>

<!-- Summary Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="0">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-label">Currently Enrolled</div>
                    <div class="stat-value">{{ $currentEnrollments->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="stat-label">Total Units</div>
                    <div class="stat-value">{{ $currentEnrollments->sum(fn($e) => $e->section->subject->units ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon purple"><i class="fas fa-history"></i></div>
                <div>
                    <div class="stat-label">History</div>
                    <div class="stat-value">{{ $historyEnrollments->count() }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon gold"><i class="fas fa-user-graduate"></i></div>
                <div>
                    <div class="stat-label">Status</div>
                    <span class="status-badge {{ $student->isAdmitted() ? 'success' : 'warning' }}">
                        {{ $student->admission_badge }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs: Current / History -->
<ul class="nav nav-tabs-modern nav-tabs mb-0" id="enrollmentTabs" role="tablist" data-aos="fade-up">
    <li class="nav-item">
        <button class="nav-link active" id="current-tab" data-bs-toggle="tab" data-bs-target="#current" type="button" role="tab">
            <i class="fas fa-bookmark me-1"></i> Current 
            <span class="sidebar-badge ms-1">{{ $currentEnrollments->count() }}</span>
        </button>
    </li>
    <li class="nav-item">
        <button class="nav-link" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
            <i class="fas fa-history me-1"></i> History
            @if($historyEnrollments->count() > 0)
                <span class="sidebar-badge ms-1">{{ $historyEnrollments->count() }}</span>
            @endif
        </button>
    </li>
</ul>

<div class="tab-content" data-aos="fade-up" data-aos-delay="100">
    <!-- Current Enrollments -->
    <div class="tab-pane fade show active" id="current" role="tabpanel">
        <div class="glass-card" style="border-top-left-radius:0;border-top-right-radius:0;">
            <div class="table-responsive">
                <table class="table-modern w-100">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Subject</th>
                            <th>Schedule</th>
                            <th>Room</th>
                            <th>Units</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currentEnrollments as $enrollment)
                            @php $section = $enrollment->section; @endphp
                            <tr>
                                <td>
                                    <span style="color:var(--gold);font-weight:600;">{{ $section->section_name }}</span>
                                </td>
                                <td style="color:var(--text-primary);font-weight:500;">
                                    {{ $section->subject->subject_name }}
                                    <div style="font-size:0.75rem;color:var(--text-muted);">
                                        <i class="fas fa-user-tie me-1"></i>{{ $section->teacher->user->name ?? 'TBA' }}
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge info">{{ $section->day }}</span>
                                    <div style="font-size:0.8rem;margin-top:4px;">
                                        {{ date('h:i A', strtotime($section->start_time)) }} - 
                                        {{ date('h:i A', strtotime($section->end_time)) }}
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fa-door-open me-1" style="color:var(--gold);font-size:0.7rem;"></i>
                                    {{ $section->room->name ?? 'TBA' }}
                                </td>
                                <td style="text-align:center;">
                                    <span style="font-weight:700;color:var(--text-primary);">{{ $section->subject->units }}</span>
                                </td>
                                <td>
                                    <form action="{{ route('enrollments.drop', $section) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger-modern btn-sm" 
                                                onclick="return confirm('Are you sure you want to drop {{ $section->subject->subject_name }}? This action cannot be undone.')">
                                            <i class="fas fa-times me-1"></i> Drop
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="padding:3rem;color:var(--text-muted);">
                                    <i class="fas fa-inbox" style="font-size:2.5rem;display:block;margin-bottom:0.75rem;opacity:0.2;"></i>
                                    <div style="font-weight:600;margin-bottom:0.25rem;">No current enrollments</div>
                                    <div style="font-size:0.85rem;">Browse offered sections to start enrolling.</div>
                                    <a href="{{ route('subjects.index') }}" class="btn btn-gold btn-sm mt-3">
                                        <i class="fas fa-book me-1"></i> Browse Schedule
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- History Tab -->
    <div class="tab-pane fade" id="history" role="tabpanel">
        <div class="glass-card" style="border-top-left-radius:0;border-top-right-radius:0;">
            <div class="table-responsive">
                <table class="table-modern w-100">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Subject</th>
                            <th>Semester</th>
                            <th>School Year</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyEnrollments as $enrollment)
                            @php $section = $enrollment->section; @endphp
                            <tr>
                                <td>
                                    <span style="color:var(--gold);font-weight:600;">{{ $section->section_name }}</span>
                                </td>
                                <td style="color:var(--text-primary);font-weight:500;">
                                    {{ $section->subject->subject_name }}
                                </td>
                                <td>Semester {{ $section->semester }}</td>
                                <td>{{ $section->school_year }}</td>
                                <td>
                                    @if($enrollment->status == 'completed')
                                        <span class="status-badge success"><i class="fas fa-check"></i> Completed</span>
                                    @elseif($enrollment->status == 'dropped')
                                        <span class="status-badge warning"><i class="fas fa-arrow-down"></i> Dropped</span>
                                    @else
                                        <span class="status-badge info">{{ ucfirst($enrollment->status) }}</span>
                                    @endif
                                </td>
                                <td style="font-size:0.85rem;">
                                    {{ $enrollment->enrollment_date ? $enrollment->enrollment_date->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center" style="padding:3rem;color:var(--text-muted);">
                                    <i class="fas fa-history" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.2;"></i>
                                    No enrollment history yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection