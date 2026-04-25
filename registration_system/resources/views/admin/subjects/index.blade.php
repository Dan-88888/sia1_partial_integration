@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-book me-2" style="color:var(--navy);"></i> Academic Catalog</h1>
            <p class="page-subtitle">Manage subject requirements and unit assignments</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-navy" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-1"></i> Import CSV
            </button>
            <a href="{{ route('admin.subjects.create') }}" class="btn btn-navy">
                <i class="fas fa-plus me-2"></i> Add New Subject
            </a>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="glass-card" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Linked Course</th>
                    <th>Units</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                <tr>
                    <td class="fw-bold">{{ $subject->subject_code }}</td>
                    <td>{{ $subject->subject_name }}</td>
                    <td>{{ $subject->course->course_code ?? 'General Education' }}</td>
                    <td>
                        <span class="badge bg-light text-indigo border">{{ $subject->units }} Units</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-navy btn-sm" title="Edit Subject">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.subjects.destroy', $subject) }}" method="POST" onsubmit="return confirm('Delete this subject?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" title="Delete Subject">
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
    
    <div class="mt-4">
        {{ $subjects->links() }}
    </div>
</div>
@endsection

@push('modals')
<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-navy" id="importModalLabel">Bulk Subject Import</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.subjects.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select CSV File</label>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                        <div class="form-text mt-2">
                            <p class="mb-1"><strong>Required CSV Format (No Headers):</strong></p>
                            <code>Course Code, Subject Code, Subject Name, Units</code>
                            <p class="mt-2 text-danger small"><i class="fas fa-exclamation-triangle me-1"></i> Aborts entire upload if any error (like invalid course code) is detected.</p>
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
