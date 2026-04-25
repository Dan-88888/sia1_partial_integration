@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title">University Overview</h1>
        <p class="page-subtitle">Administrative dashboard for Partido State University</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.sections.create') }}" class="btn btn-navy btn-sm">
            <i class="fas fa-plus me-1"></i> New Section
        </a>
        <a href="{{ route('admin.audit.index') }}" class="btn btn-sm border text-secondary">
            <i class="fas fa-clipboard-list me-1"></i> Audit Logs
        </a>
    </div>
</div>

<!-- Stats Grid — Row 1 -->
<div class="row g-4 mb-4" data-aos="fade-up">
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $studentsCount }}</div>
                    <div class="stat-label">Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon green">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $teachersCount }}</div>
                    <div class="stat-label">Faculty</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon gold">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $appPending }}</div>
                    <div class="stat-label">Pending</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #f3e8ff; color: #7c3aed;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $sectionsCount }}</div>
                    <div class="stat-label">Sections</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-book-reader"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $enrollmentsCount }}</div>
                    <div class="stat-label">Enrolled</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #fef2f2; color: #dc2626;">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $pendingPayments }}</div>
                    <div class="stat-label">Unpaid</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Application Pipeline + Enrollment Chart -->
<div class="row g-4 mb-4">
    <!-- Application Pipeline -->
    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="50">
        <div class="glass-card h-100">
            <div class="card-header-modern mb-4">
                <i class="fas fa-stream me-2" style="color:var(--navy);"></i>
                <h4 class="mb-0">Application Pipeline</h4>
            </div>
            
            @php $totalApps = $appPending + $appApproved + $appRejected; @endphp
            
            @if($totalApps > 0)
            <div class="d-flex gap-2 mb-4" style="height: 12px; border-radius: 8px; overflow: hidden; background: #f1f5f9;">
                @if($appApproved > 0)
                <div style="width: {{ ($appApproved/$totalApps)*100 }}%; background: #22c55e; border-radius: 8px;" title="{{ $appApproved }} Approved"></div>
                @endif
                @if($appPending > 0)
                <div style="width: {{ ($appPending/$totalApps)*100 }}%; background: #f59e0b;" title="{{ $appPending }} Pending"></div>
                @endif
                @if($appRejected > 0)
                <div style="width: {{ ($appRejected/$totalApps)*100 }}%; background: #ef4444;" title="{{ $appRejected }} Rejected"></div>
                @endif
            </div>
            @endif

            <div class="d-flex flex-column gap-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block;"></span>
                        <span style="font-size:0.9rem; font-weight:600;">Approved</span>
                    </div>
                    <span class="fw-bold">{{ $appApproved }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:#f59e0b;display:inline-block;"></span>
                        <span style="font-size:0.9rem; font-weight:600;">Pending Review</span>
                    </div>
                    <span class="fw-bold">{{ $appPending }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block;"></span>
                        <span style="font-size:0.9rem; font-weight:600;">Rejected</span>
                    </div>
                    <span class="fw-bold">{{ $appRejected }}</span>
                </div>
            </div>

            @if($pendingAmount > 0)
            <div class="mt-4 p-3" style="background:#fffbeb; border-radius:10px; border:1px solid #fde68a;">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:0.85rem; font-weight:600; color:#92400e;"><i class="fas fa-coins me-2"></i>Pending Revenue</span>
                    <span style="font-size:1.1rem; font-weight:800; color:#92400e;">₱{{ number_format($pendingAmount, 2) }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Enrollment by Course Chart -->
    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card h-100">
            <div class="card-header-modern mb-4">
                <i class="fas fa-chart-bar me-2" style="color:var(--navy);"></i>
                <h4 class="mb-0">Students by Program</h4>
            </div>
            
            @if($enrollmentByCourse->count() > 0)
            <canvas id="courseChart" height="200"></canvas>
            @else
            <div class="text-center py-5 text-muted">
                <i class="fas fa-chart-bar fa-2x mb-3" style="opacity:0.3;"></i>
                <p>No enrollment data available yet.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Row 3: Recent Applications + Recent Enrollments -->
<div class="row g-4 mb-4">
    <!-- Recent Applications -->
    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="150">
        <div class="glass-card">
            <div class="card-header-modern mb-4">
                <i class="fas fa-tasks me-2" style="color:var(--navy);"></i>
                <h4 class="mb-0">Recent Applications</h4>
                <a href="{{ route('admin.registration.records') }}" class="ms-auto" style="font-size:0.8rem; font-weight:600; color:var(--navy); text-decoration:none;">View All →</a>
            </div>

            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications->take(5) as $app)
                        <tr>
                            <td class="fw-bold">{{ ucfirst($app->type) }}</td>
                            <td>{{ $app->name }}</td>
                            <td>{{ $app->email }}</td>
                            <td>
                                <span class="status-badge {{ $app->status === 'Pending' ? 'warning' : ($app->status === 'Approved' ? 'success' : 'danger') }}">
                                    {{ $app->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    @if($app->status === 'Pending')
                                        <form action="{{ route('admin.applications.approve', $app->id) }}" method="POST" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn btn-success-modern btn-sm btn-sm-action" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.applications.reject', $app->id) }}" method="POST" class="inline-form">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm text-white btn-sm-action" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger-modern btn-sm btn-sm-action" title="Delete">
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
        </div>
    </div>

    <!-- Recent Enrollments -->
    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="200">
        <div class="glass-card">
            <div class="card-header-modern mb-4">
                <i class="fas fa-book-reader me-2" style="color:var(--navy);"></i>
                <h4 class="mb-0">Recent Enrollments</h4>
            </div>
            
            @forelse($recentEnrollments as $enrollment)
            <div class="d-flex align-items-center gap-3 {{ !$loop->last ? 'mb-3 pb-3' : '' }}" style="{{ !$loop->last ? 'border-bottom: 1px solid #f1f5f9;' : '' }}">
                <div style="width:36px;height:36px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fas fa-user-check" style="color:#3b82f6; font-size:0.8rem;"></i>
                </div>
                <div style="flex:1; min-width:0;">
                    <div style="font-weight:700; font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $enrollment->student->user->name ?? 'N/A' }}
                    </div>
                    <div style="font-size:0.75rem; color:var(--text-muted);">
                        {{ $enrollment->section->subject->subject_code ?? '' }} — {{ $enrollment->section->subject->subject_name ?? '' }}
                    </div>
                </div>
                <small class="text-muted" style="flex-shrink:0; font-size:0.7rem;">{{ $enrollment->created_at->diffForHumans() }}</small>
            </div>
            @empty
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox mb-2" style="font-size:1.5rem; opacity:0.3;"></i>
                <p class="mb-0" style="font-size:0.85rem;">No recent enrollments</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.partials.application_js')

@if($enrollmentByCourse->count() > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('courseChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($enrollmentByCourse->pluck('course')) !!},
            datasets: [{
                label: 'Students',
                data: {!! json_encode($enrollmentByCourse->pluck('total')) !!},
                backgroundColor: [
                    '#1b2a4a', '#3b82f6', '#7c3aed', '#059669',
                    '#f59e0b', '#ef4444', '#06b6d4', '#8b5cf6'
                ],
                borderRadius: 6,
                borderSkipped: false,
                barThickness: 32,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1b2a4a',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: { size: 13, weight: '700' },
                    bodyFont: { size: 12 }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { font: { size: 11, weight: '600' }, color: '#64748b' }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 12, weight: '700' }, color: '#1e293b' }
                }
            }
        }
    });
</script>
@endif
@endsection
