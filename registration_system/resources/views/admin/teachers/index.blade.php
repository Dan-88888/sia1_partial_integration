@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-chalkboard-teacher me-2" style="color:var(--navy);"></i> Faculty Management</h1>
            <p class="page-subtitle">View and manage all faculty members and teaching staff</p>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card mb-4" style="padding: 1rem 1.5rem;" data-aos="fade-up">
    <form method="GET" action="{{ route('admin.teachers.index') }}" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Search</label>
            <input type="text" name="search" class="form-modern-input py-1" style="font-size: 0.9rem;" placeholder="Name, email, teacher ID..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">Campus</label>
            <select name="campus" class="form-modern-input py-1" style="font-size: 0.9rem;">
                <option value="">All Campuses</option>
                @foreach($campuses as $campus)
                    <option value="{{ $campus }}" {{ request('campus') == $campus ? 'selected' : '' }}>{{ $campus }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-modern-label" style="font-size: 0.8rem; margin-bottom:2px;">College</label>
            <select name="college" class="form-modern-input py-1" style="font-size: 0.9rem;">
                <option value="">All Colleges</option>
                @foreach($colleges as $college)
                    <option value="{{ $college }}" {{ request('college') == $college ? 'selected' : '' }}>{{ $college }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2 d-flex gap-1 justify-content-end">
            <button type="submit" class="btn btn-navy btn-sm py-1 px-3 w-100">
                <i class="fas fa-search me-1"></i> Search
            </button>
            @if(request()->hasAny(['search', 'campus', 'college']))
                <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm border text-secondary px-2 d-flex align-items-center" title="Clear Filters">
                    <i class="fas fa-undo"></i>
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Main Content Card -->
<div class="glass-card mb-5" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th style="width: 120px;">Teacher ID</th>
                    <th>Full Name</th>
                    <th>Campus</th>
                    <th>College/Department</th>
                    <th>Email Address</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($teachers as $teacher)
                <tr>
                    <td class="fw-bold text-navy">{{ $teacher->teacher_id }}</td>
                    <td>{{ $teacher->user->name ?? 'N/A' }}</td>
                    <td><span class="badge bg-light text-primary border">{{ $teacher->campus ?? 'N/A' }}</span></td>
                    <td><span class="text-muted small">{{ $teacher->college ?? 'Unassigned' }}</span></td>
                    <td>{{ $teacher->user->email ?? 'N/A' }}</td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-navy btn-sm" style="padding: 4px 8px;" title="Edit Profile">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" style="padding: 4px 8px;" title="Delete Teacher">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                        <span class="text-muted">No faculty records found.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($teachers->hasPages())
    <div class="px-4 py-3 border-top bg-light/30">
        {{ $teachers->links() }}
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
