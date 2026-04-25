@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-user-graduate me-2" style="color:var(--navy);"></i> Students Management</h1>
            <p class="page-subtitle">View and manage all enrolled students in the university system</p>
        </div>
        </button>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card mb-4" style="padding: 1rem 1.5rem; margin-top: 1rem;" data-aos="fade-up">
    <form method="GET" action="{{ route('admin.students.index') }}" class="row g-2 align-items-end">
        <input type="hidden" name="year_level" value="{{ request('year_level') }}">
        <div class="col-md-3">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Search</label>
            <input type="text" name="search" class="form-modern-input" style="padding: 6px 12px; font-size: 0.9rem;" placeholder="Name, student number..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Campus</label>
            <select name="campus" class="form-modern-input" style="padding: 6px 12px; font-size: 0.9rem;">
                <option value="">All Campuses</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>{{ $campus }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">College</label>
            <select name="college" class="form-modern-input" style="padding: 6px 12px; font-size: 0.9rem;">
                <option value="">All Colleges</option>
                @foreach($colleges as $college)
                    <option value="{{ $college }}" {{ request('college') == $college ? 'selected' : '' }}>{{ $college }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Course</label>
            <select name="course" class="form-modern-input" style="padding: 6px 12px; font-size: 0.9rem;">
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ $course }}" {{ request('course') == $course ? 'selected' : '' }}>{{ $course }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-navy btn-sm px-4 w-100">
                <i class="fas fa-search me-1"></i> Search
            </button>
            @if(request()->hasAny(['search', 'course', 'year_level', 'campus', 'college']))
                <a href="{{ route('admin.students.index') }}" class="btn btn-sm border text-secondary px-3 d-flex align-items-center" title="Clear Filters">
                    <i class="fas fa-undo"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Year Level Tabs -->
<ul class="nav nav-pills mb-4 modern-tabs" data-aos="fade-up">
    <li class="nav-item">
        <a class="nav-link {{ request('year_level') == '' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['year_level' => '']) }}">
            All Years
        </a>
    </li>
    @for($i = 1; $i <= 6; $i++)
    <li class="nav-item">
        <a class="nav-link {{ request('year_level') == $i ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['year_level' => $i]) }}">
            {{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} Year
        </a>
    </li>
    @endfor
</ul>

<!-- Main Content Card -->
<div class="glass-card mb-5" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 140px;">Student ID</th>
                    <th>Full Name</th>
                    <th>Campus</th>
                    <th>College</th>
                    <th>Course & Year</th>
                    <th>Email Address</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr>
                    <td class="fw-bold text-navy">{{ $student->student_number }}</td>
                    <td>{{ $student->user->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-light text-primary border">{{ $student->campus ?? 'N/A' }}</span></td>
                    <td><span class="text-muted small">{{ \Str::limit($student->college, 25) }}</span></td>
                    <td>
                        <div class="fw-bold">{{ $student->course }}</div>
                        <div class="small text-muted">{{ $student->year_level }}{{ $student->year_level == 1 ? 'st' : ($student->year_level == 2 ? 'nd' : ($student->year_level == 3 ? 'rd' : 'th')) }} Year</div>
                    </td>
                    <td>{{ $student->user->email ?? 'N/A' }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-navy btn-sm" style="padding: 4px 8px;" title="Edit Profile">
                                <i class="fas fa-pen"></i>
                            </a>
                            <a href="{{ route('admin.students.enrollment_data', $student->id) }}" class="btn btn-sm border text-navy" style="padding: 4px 8px; border-color: var(--navy) !important;" title="Enrollment Data">
                                <i class="fas fa-file-invoice"></i>
                            </a>
                            <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" style="padding: 4px 8px;" title="Delete Student">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <span class="text-muted">No student records found.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($students->hasPages())
    <div class="px-4 py-3 border-top bg-light/30">
        {{ $students->links() }}
    </div>
    @endif
</div>

<style>
/* Modern Tabs Styling */
.modern-tabs {
    border-bottom: 2px solid rgba(226, 232, 240, 0.8);
    gap: 0.5rem;
}
.modern-tabs .nav-link {
    color: #64748b;
    font-weight: 500;
    border-radius: 8px 8px 0 0;
    padding: 0.75rem 1.5rem;
    transition: all 0.3s ease;
    border: none;
    border-bottom: 3px solid transparent;
    margin-bottom: -2px;
}
.modern-tabs .nav-link:hover {
    color: var(--navy);
    background-color: rgba(241, 245, 249, 0.5);
}
.modern-tabs .nav-link.active {
    color: var(--navy);
    background-color: transparent;
    border-bottom: 3px solid var(--accent);
}
</style>
@endsection

@push('modals')
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-navy" id="importModalLabel">Bulk Student Import</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        <div class="form-text mt-2">
                            <p class="mb-1"><strong>Required CSV Format (No Headers):</strong></p>
                            <code>Name, Email, Course, Year Level</code>
                            <p class="mt-2 text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> Aborts entire upload if any error (like duplicate email) is detected.</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-navy">Start Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
