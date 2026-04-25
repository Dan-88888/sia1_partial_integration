@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-user-shield me-2" style="color:var(--navy);"></i> Admin Users</h1>
            <p class="page-subtitle">Manage administrator accounts with system-level access</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" class="btn btn-navy btn-sm">
                <i class="fas fa-plus me-1"></i> New Admin
            </a>
        </div>
    </div>
</div>

<!-- Search Bar -->
<div class="glass-card mb-4" style="padding: 1.25rem 2rem;" data-aos="fade-up">
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3 align-items-end">
        <div class="col-md-8">
            <label class="form-modern-label" style="margin-bottom:4px;">Search</label>
            <input type="text" name="search" class="form-modern-input" placeholder="Search by name or email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-navy btn-sm px-4">
                <i class="fas fa-search me-1"></i> Search
            </button>
            @if(request('search'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm border text-secondary px-3">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Main Table Card -->
<div class="glass-card" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($admins as $admin)
                <tr>
                    <td>{{ $admin->id }}</td>
                    <td class="fw-bold">
                        {{ $admin->name }}
                        @if($admin->id === Auth::id())
                            <span class="status-badge success ms-2" style="font-size:0.6rem;">YOU</span>
                        @endif
                    </td>
                    <td>{{ $admin->email }}</td>
                    <td><small class="text-muted">{{ $admin->created_at->format('M d, Y') }}</small></td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.edit', $admin->id) }}" class="btn btn-navy btn-sm" title="Edit Admin">
                                <i class="fas fa-pen me-1"></i> Edit
                            </a>
                            @if($admin->id !== Auth::id())
                            <form action="{{ route('admin.users.destroy', $admin->id) }}" method="POST" class="inline-form" onsubmit="return confirm('Are you sure you want to delete this admin account?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" title="Delete Admin">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox me-2"></i> No admin accounts found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($admins->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #f1f5f9;">
        <small class="text-muted">Showing {{ $admins->firstItem() }}–{{ $admins->lastItem() }} of {{ $admins->total() }} admins</small>
        {{ $admins->links() }}
    </div>
    @endif
</div>
@endsection
