@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-history me-2" style="color:var(--navy);"></i> Registration Records</h1>
            <p class="page-subtitle">Full historical log of all portal admission applications and their outcomes</p>
        </div>
        <div>
            <form action="{{ route('admin.applications.clear_all') }}" method="POST" id="clearAllForm" onsubmit="return confirm('WARNING: This will permanently delete ALL application records. Are you sure?');">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-broom me-1"></i> Clear All History
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Search & Filter Bar -->
<div class="glass-card mb-4" style="padding: 1.25rem 2rem;" data-aos="fade-up">
    <form method="GET" action="{{ route('admin.registration.records') }}" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-modern-label" style="margin-bottom:4px;">Search</label>
            <input type="text" name="search" class="form-modern-input" placeholder="Name, email, tracking number..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="margin-bottom:4px;">Status</label>
            <select name="status" class="form-modern-input">
                <option value="">All Status</option>
                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-modern-label" style="margin-bottom:4px;">Type</label>
            <select name="type" class="form-modern-input">
                <option value="">All Types</option>
                <option value="student" {{ request('type') == 'student' ? 'selected' : '' }}>Student</option>
                <option value="teacher" {{ request('type') == 'teacher' ? 'selected' : '' }}>Teacher</option>
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-navy btn-sm px-4">
                <i class="fas fa-search me-1"></i> Search
            </button>
            @if(request()->hasAny(['search', 'status', 'type']))
                <a href="{{ route('admin.registration.records') }}" class="btn btn-sm border text-secondary px-3">
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
                    <th>Record ID</th>
                    <th>App Type</th>
                    <th>Applicant Name</th>
                    <th>Course/Year</th>
                    <th>Login Credential</th>
                    <th>Email Address</th>
                    <th>Final Status</th>
                    <th>Submitted Date</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>#{{ $app->id }}</td>
                    <td class="fw-bold">{{ ucfirst($app->type) }}</td>
                    <td>{{ $app->name }}</td>
                    <td>
                        @if($app->type === 'student')
                            <span class="badge bg-light text-dark border">{{ $app->course }} - Yr {{ $app->year_level }}</span>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <div style="font-family:monospace; font-size:0.85rem;">
                            <div class="text-navy fw-bold" style="border-bottom:1px dashed #ddd; padding-bottom:2px; margin-bottom:2px;">
                                <i class="fas fa-user-circle me-1"></i>{{ $app->university_email ?? $app->email }}
                            </div>
                            @if($app->temp_password)
                                <div class="text-gold">
                                    <i class="fas fa-key me-1"></i>{{ $app->temp_password }}
                                </div>
                            @else
                                <small class="text-muted">Password Pending</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="text-muted" style="font-size:0.8rem;">
                            {{ $app->university_email ?? $app->email }}
                        </div>
                    </td>
                    <td>
                        <span class="status-badge {{ $app->status === 'Pending' ? 'warning' : ($app->status === 'Approved' ? 'success' : 'danger') }}">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td><small class="text-muted">{{ $app->created_at->format('M d, Y H:i') }}</small></td>
                    <td class="text-end admin-actions">
                        <div class="d-flex justify-content-end gap-1">
                            @if($app->status === 'Pending')
                                <form action="{{ route('admin.applications.approve', $app->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-success-modern btn-sm btn-sm-action" title="Approve & Create User">
                                        <i class="fas fa-check me-1"></i> Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.applications.reject', $app->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm text-white btn-sm-action" title="Reject Application">
                                        <i class="fas fa-times me-1"></i> Reject
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.applications.destroy', $app->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm btn-sm-action" title="Remove from History">
                                    <i class="fas fa-trash-alt me-1"></i> Delete
                                </button>
                            </form>
                            @if(!empty($app->documents))
                                <button type="button" class="btn btn-outline-navy btn-sm btn-sm-action" 
                                        onclick="showDocs('{{ addslashes($app->name) }}', {!! json_encode($app->documents) !!})" 
                                        title="View Uploaded Documents">
                                    <i class="fas fa-file-pdf me-1"></i> Files
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox me-2"></i> No application records found matching your criteria.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 pt-3" style="border-top: 1px solid #f1f5f9;">
        <small class="text-muted">Showing {{ $applications->firstItem() }}–{{ $applications->lastItem() }} of {{ $applications->total() }} records</small>
        {{ $applications->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
@include('admin.partials.application_js')
<!-- Document Viewer Modal -->
<div class="modal fade" id="viewDocsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:15px; overflow:hidden;">
            <div class="modal-header bg-navy text-white py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-folder-open me-2"></i> Application Documents: <span id="docsModalName"></span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div id="docsList" class="row g-3">
                    <!-- Files will be injected here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function showDocs(name, docs) {
    const modal = new bootstrap.Modal(document.getElementById('viewDocsModal'));
    document.getElementById('docsModalName').textContent = name;
    const list = document.getElementById('docsList');
    list.innerHTML = '';

    docs.forEach((path, index) => {
        const col = document.createElement('div');
        col.className = 'col-md-6';
        
        const fileUrl = `/storage/${path}`;
        const fileName = path.split('/').pop();
        const isImage = /\.(jpg|jpeg|png|webp|gif)$/i.test(path);

        let content = '';
        if (isImage) {
            content = `
                <div class="card h-100 border-0 shadow-sm overflow-hidden" style="border-radius:10px;">
                    <a href="${fileUrl}" target="_blank">
                        <img src="${fileUrl}" class="card-img-top" style="height:150px; object-fit:cover; border-bottom:1px solid #eee;">
                    </a>
                    <div class="card-body p-2 text-center bg-white">
                        <small class="text-truncate d-block mb-1 text-muted" style="max-width:100%">${fileName}</small>
                        <a href="${fileUrl}" target="_blank" class="btn btn-outline-navy btn-xs w-100 py-1">View Full Image</a>
                    </div>
                </div>
            `;
        } else {
            content = `
                <div class="card h-100 border-0 shadow-sm" style="border-radius:10px;">
                    <div class="card-body p-3 d-flex align-items-center">
                        <div class="bg-light rounded p-3 me-3" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center;">
                            <i class="fas fa-file-pdf text-danger fa-2x"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <small class="text-truncate d-block mb-1 fw-bold" style="max-width:100%">${fileName}</small>
                            <a href="${fileUrl}" target="_blank" class="btn btn-outline-danger btn-xs py-1 px-3">Open PDF</a>
                        </div>
                    </div>
                </div>
            `;
        }
        
        col.innerHTML = content;
        list.appendChild(col);
    });

    modal.show();
}
</script>
@endsection
