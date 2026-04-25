@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-chart-line me-2" style="color:var(--gold);"></i> My Academic Records</h1>
        <p class="page-subtitle">View your grades and General Weighted Average (GWA)</p>
    </div>
</div>

<!-- GWA Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-12" data-aos="fade-up">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon gold"><i class="fas fa-award"></i></div>
                <div>
                    <div class="stat-label">General Weighted Average (GWA)</div>
                    <div class="stat-value">{{ number_format($gwa, 3) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Grades Table -->
<div class="glass-card" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header-modern">
        <i class="fas fa-list-ol"></i>
        <h4>Grade Report per Section</h4>
    </div>
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Section</th>
                    <th>Units</th>
                    <th style="text-align:center;">Midterm</th>
                    <th style="text-align:center;">Final</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                    <tr>
                        <td style="color:var(--text-primary);font-weight:500;">
                            {{ $grade['subject']->subject_name }}
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $grade['subject']->subject_code }}</div>
                        </td>
                        <td><span style="color:var(--gold);font-weight:600;">{{ $grade['section']->section_name }}</span></td>
                        <td style="text-align:center;font-weight:700;">{{ $grade['units'] }}</td>
                        <td style="text-align:center;font-weight:700;color:var(--gold);">{{ $grade['midterm'] ?? '---' }}</td>
                        <td style="text-align:center;font-weight:700;color:var(--text-primary);">{{ $grade['final'] ?? '---' }}</td>
                        <td>
                            @if($grade['final'])
                                <span class="status-badge {{ $grade['final'] <= 3.0 ? 'success' : 'danger' }}">
                                    {{ $grade['remarks'] ?: ($grade['final'] <= 3.0 ? 'PASSED' : 'FAILED') }}
                                </span>
                            @else
                                <span class="status-badge info">PENDING</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:3rem;color:var(--text-muted);">
                            <i class="fas fa-graduation-cap" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.2;"></i>
                            No grades available yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection