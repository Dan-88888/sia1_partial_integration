@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">
            <i class="fas fa-chalkboard-teacher me-2" style="color:var(--gold);"></i>
            Faculty Dashboard
        </h2>
        <p class="page-subtitle">Welcome back, <strong>Prof. {{ Auth::user()->name }}</strong>. Here's your academic overview.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-sm border text-secondary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print Schedule
        </button>
    </div>
</div>

<!-- Stats Horizontal Grid -->
<div class="row g-4 mb-4" data-aos="fade-up">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon blue">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $totalStudents }}</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon purple" style="background: #f3e8ff; color: #7c3aed;">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $sections->count() }}</div>
                    <div class="stat-label">Total Sections</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon green" style="background: #ecfdf5; color: #059669;">
                    <i class="fas fa-user-check"></i>
                </div>
                <div>
                    <div class="stat-value">{{ number_format($avgAttendance, 1) }}%</div>
                    <div class="stat-label">Avg Attendance</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon gold">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <div class="stat-value">{{ $passingCount }}</div>
                    <div class="stat-label">Passing Students</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Today's Schedule -->
    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="100">
        <div class="glass-card h-100 p-4">
            <div class="card-header-modern mb-4">
                <i class="fas fa-calendar-day" style="color:var(--navy);"></i>
                <h4 class="mb-0">Today's Schedule <span class="badge bg-light text-dark ms-2" style="font-size:0.7rem;">{{ now()->format('l, M d') }}</span></h4>
            </div>

            @forelse($todayClasses as $class)
            <div class="d-flex align-items-center gap-4 p-3 mb-3 border-start border-4" style="background: #f8fafc; border-color: var(--navy) !important; border-radius: 4px 10px 10px 4px;">
                <div class="text-center" style="min-width: 80px;">
                    <div class="fw-bold" style="font-size: 0.9rem; color: var(--navy);">{{ date('h:i', strtotime($class->start_time)) }}</div>
                    <div class="text-muted small">{{ date('A', strtotime($class->start_time)) }}</div>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold text-navy">{{ $class->subject->subject_code }}: {{ $class->subject->subject_name }}</h6>
                    <div class="d-flex gap-3 small text-muted">
                        <span><i class="fas fa-door-open me-1"></i> {{ $class->room->name ?? 'TBA' }}</span>
                        <span><i class="fas fa-users me-1"></i> {{ $class->section_name }}</span>
                    </div>
                </div>
                <div>
                    <a href="{{ route('teacher.sections.attendance', $class) }}" class="btn btn-navy btn-sm">Take Attendance</a>
                </div>
            </div>
            @empty
            <div class="text-center py-5 text-muted">
                <i class="fas fa-coffee fa-3x mb-3 opacity-20"></i>
                <p>No classes scheduled for today. Enjoy your day!</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Performance Visualization -->
    <div class="col-lg-5" data-aos="fade-up" data-aos-delay="150">
        <div class="glass-card h-100 p-4">
            <div class="card-header-modern mb-4">
                <i class="fas fa-chart-pie" style="color:var(--navy);"></i>
                <h4 class="mb-0">Student Performance</h4>
            </div>
            <div style="height: 250px; position: relative;">
                @if($passingCount > 0 || $failingCount > 0)
                    <canvas id="performanceChart"></canvas>
                @else
                    <div class="d-flex align-items-center justify-content-center h-100 text-muted small">
                        No grades recorded yet this term.
                    </div>
                @endif
            </div>
            <div class="mt-4 d-flex justify-content-around small">
                <div class="d-flex align-items-center gap-2">
                    <span style="width:12px; height:12px; background:#22c55e; border-radius:3px;"></span> Passing
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="width:12px; height:12px; background:#ef4444; border-radius:3px;"></span> Failing
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span style="width:12px; height:12px; background:#e2e8f0; border-radius:3px;"></span> Ungraded
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assigned Classes Table -->
<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="200">
    <div class="card-header-modern mb-4">
        <i class="fas fa-list-ul" style="color:var(--navy);"></i>
        <h4 class="mb-0">All Assigned Sections</h4>
    </div>
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Schedule & Room</th>
                    <th>Enrolled</th>
                    <th class="text-center">Quick Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sections as $section)
                <tr>
                    <td class="fw-bold">{{ $section->section_name }}</td>
                    <td>
                        <div class="fw-bold">{{ $section->subject->subject_code }}</div>
                        <small class="text-muted">{{ $section->subject->subject_name }}</small>
                    </td>
                    <td>
                        <div class="small fw-600"><i class="far fa-calendar-alt text-primary me-1"></i> {{ $section->day }}</div>
                        <div class="small text-muted">
                            <i class="far fa-clock me-1"></i> {{ date('h:i A', strtotime($section->start_time)) }} - {{ date('h:i A', strtotime($section->end_time)) }}
                            <span class="ms-2"><i class="fas fa-door-open text-warning me-1"></i> {{ $section->room->name ?? 'TBA' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-light text-dark border">{{ $section->enrollments()->where('status', 'enrolled')->count() }} Students</span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('teacher.sections.attendance', $section) }}" class="btn btn-sm btn-outline-navy transition-all" title="Manage Attendance">
                                <i class="fas fa-user-check"></i>
                            </a>
                            <a href="{{ route('teacher.sections.grades', $section) }}" class="btn btn-sm btn-navy transition-all" title="Manage Grades">
                                <i class="fas fa-star-half-alt"></i>
                            </a>
                            <a href="{{ route('teacher.sections.roster.download', $section) }}" class="btn btn-sm btn-light border transition-all" title="Download Roster">
                                <i class="fas fa-file-download"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- Class Announcements -->
<div class="glass-card p-4 mt-4" data-aos="fade-up" data-aos-delay="250">
    <div class="card-header-modern mb-4 align-items-center d-flex">
        <i class="fas fa-bullhorn" style="color:var(--navy);"></i>
        <h4 class="mb-0 ms-2">Recent Announcements</h4>
        <div class="ms-auto">
            <button class="btn btn-sm btn-navy" data-bs-toggle="modal" data-bs-target="#newAnnouncementModal">
                <i class="fas fa-plus me-1"></i> New Announcement
            </button>
        </div>
    </div>

    @if($announcements->count() > 0)
        <div class="d-flex flex-column gap-3">
            @foreach($announcements as $announcement)
            <div class="p-3 rounded shadow-sm" style="background: rgba(255,255,255,0.8); border: 1px solid var(--glass-border); border-left: 4px solid {{ $announcement->priority === 'high' ? '#eab308' : 'var(--navy)' }};">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 fw-bold">{{ $announcement->title }}</h6>
                    <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                </div>
                @if($announcement->section)
                    <div class="mb-2"><span class="badge bg-light text-dark border">{{ $announcement->section->section_name }}</span></div>
                @else
                    <div class="mb-2"><span class="badge" style="background:var(--navy);">All Sections</span></div>
                @endif
                <p class="mb-0 mt-2 text-secondary" style="font-size: 0.9rem;">{!! nl2br(e($announcement->content)) !!}</p>
            </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-4 text-muted border rounded" style="background: rgba(255,255,255,0.3);">
            <i class="fas fa-bell-slash fa-2x mb-3 opacity-50"></i>
            <p class="mb-0">No announcements posted yet.</p>
        </div>
    @endif
</div>

<!-- New Announcement Modal -->
<div class="modal fade" id="newAnnouncementModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none; border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Post Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teacher.announcements.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Target Section</label>
                        <select name="section_id" class="form-select">
                            <option value="">All My Sections</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}">{{ $section->section_name }} ({{ $section->subject->subject_code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" required placeholder="e.g., Change of Room, Midterm Outline">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="normal">Normal</option>
                            <option value="high">High (Urgent)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message Content</label>
                        <textarea name="content" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-navy"><i class="fas fa-paper-plane me-1"></i> Post Announcement</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    @if($passingCount > 0 || $failingCount > 0)
    const ctx = document.getElementById('performanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Passing', 'Failing', 'Ungraded'],
            datasets: [{
                data: [{{ $passingCount }}, {{ $failingCount }}, {{ $ungradedCount }}],
                backgroundColor: ['#22c55e', '#ef4444', '#e2e8f0'],
                borderWidth: 0,
                cutout: '75%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
    @endif
</script>
<style>
    .fw-600 { font-weight: 600; }
    .transition-all:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
</style>
@endsection
