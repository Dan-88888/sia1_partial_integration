@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">
            <i class="fas fa-history me-2" style="color:var(--gold);"></i>
            Attendance History
        </h2>
        <p class="page-subtitle">Historical records for <strong>{{ $section->subject->subject_code }}: {{ $section->section_name }}</strong></p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.sections.attendance', $section) }}" class="btn btn-navy bg-transparent text-secondary border">
            <i class="fas fa-arrow-left me-2"></i> Back to Today
        </a>
    </div>
</div>

<!-- History Matrix Card -->
<div class="glass-card p-4" data-aos="fade-up">
    <div class="card-header-modern mb-4">
        <i class="fas fa-table" style="color:var(--navy);"></i>
        <h4 class="mb-0">Attendance Log</h4>
    </div>

    @if(count($history) > 0)
    <div class="table-responsive">
        <table class="table-modern w-100" style="font-size: 0.85rem;">
            <thead>
                <tr>
                    <th style="min-width: 200px;">Student Name</th>
                    @foreach($attendanceDates as $dateObj)
                    <th class="text-center" style="min-width: 100px;">
                        <div style="font-size: 0.7rem; color: var(--text-muted);">{{ date('M d', strtotime($dateObj->date)) }}</div>
                        <div style="font-size: 0.65rem;">{{ date('Y', strtotime($dateObj->date)) }}</div>
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($students as $enrollment)
                <tr>
                    <td class="fw-bold">
                        {{ $enrollment->student->user->name }}
                        <div class="small text-muted" style="font-weight: normal; font-size: 0.7rem;">ID: {{ $enrollment->student->student_number }}</div>
                    </td>
                    @foreach($attendanceDates as $dateObj)
                        @php 
                            $status = $history[$dateObj->date][$enrollment->student_id]->status ?? 'N/A';
                            $badgeClass = match($status) {
                                'Present' => 'success',
                                'Absent' => 'danger',
                                'Late' => 'warning',
                                'Excused' => 'info',
                                default => 'light'
                            };
                            $icon = match($status) {
                                'Present' => 'check',
                                'Absent' => 'times',
                                'Late' => 'clock',
                                'Excused' => 'envelope-open-text',
                                default => 'minus'
                            };
                        @endphp
                        <td class="text-center">
                            <span class="status-badge {{ $badgeClass }}" style="font-size: 0.65rem; padding: 0.2rem 0.5rem; min-width: 70px;">
                                <i class="fas fa-{{ $icon }} me-1" style="font-size: 0.6rem;"></i> {{ $status }}
                            </span>
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4 pt-3 d-flex justify-content-between align-items-center" style="border-top: 1px solid #f1f5f9;">
        <small class="text-muted">Showing {{ $attendanceDates->firstItem() }}–{{ $attendanceDates->lastItem() }} of {{ $attendanceDates->total() }} recorded days</small>
        {{ $attendanceDates->links() }}
    </div>
    @else
    <div class="text-center py-5 text-muted">
        <i class="fas fa-inbox fa-3x mb-3 opacity-20"></i>
        <p>No historical attendance records found for this section.</p>
    </div>
    @endif
</div>

<!-- Legend Card -->
<div class="row mt-4" data-aos="fade-up" data-aos-delay="100">
    <div class="col-md-6">
        <div class="glass-card p-3">
            <h6 class="fw-bold text-navy mb-3"><i class="fas fa-info-circle me-2"></i>Status Legend</h6>
            <div class="d-flex flex-wrap gap-3">
                <div class="d-flex align-items-center gap-2 small">
                    <span class="status-badge success" style="width: 12px; height: 12px; border-radius: 3px; padding: 0;"></span> Present
                </div>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="status-badge danger" style="width: 12px; height: 12px; border-radius: 3px; padding: 0;"></span> Absent
                </div>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="status-badge warning" style="width: 12px; height: 12px; border-radius: 3px; padding: 0;"></span> Late
                </div>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="status-badge info" style="width: 12px; height: 12px; border-radius: 3px; padding: 0;"></span> Excused
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
