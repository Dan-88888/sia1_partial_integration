@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">Grade Management</h2>
        <p class="page-subtitle">{{ $section->subject->subject_name }} ({{ $section->name ?? $section->section_name }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.sections.roster.download', $section) }}" class="btn-outline-gold">
            <i class="fas fa-file-pdf me-1"></i> Download Class Roster
        </a>
        <a href="{{ route('teacher.dashboard') }}" class="btn-outline-navy">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header-modern mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <i class="fas fa-star-half-alt"></i>
                <h3 class="mb-0" style="display:inline-block; margin-left:10px;">Student Grades</h3>
            </div>
            
<div class="glass-card" data-aos="fade-up">
    <div class="card-header-modern mb-4 d-flex align-items-center">
        <i class="fas fa-award" style="color:var(--navy);"></i>
        <h4 class="mb-0">Grade Record</h4>
        <div class="ms-auto d-flex align-items-center gap-3">
            @if($section->grades_published)
                <span class="status-badge success px-3 py-2"><i class="fas fa-check-circle me-1"></i> Grades Published</span>
            @else
                <span class="status-badge warning px-3 py-2"><i class="fas fa-edit me-1"></i> Draft Mode</span>
                <form action="{{ route('teacher.sections.publish', $section) }}" method="POST" onsubmit="return confirm('WARNING: Publishing grades is final. Students will be able to see their grades and you will no longer be able to edit them. Continue?');">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-navy"><i class="fas fa-upload me-1"></i> Publish All</button>
                </form>
            @endif
        </div>
    </div>

    @if(!$section->grades_published)
    <div class="alert-modern alert-info alert mb-4 py-2 px-3" style="font-size: 0.85rem;">
        <i class="fas fa-info-circle me-2"></i> Note: Passing grade is <strong>75.0</strong>. Grades are only available to students after you click <strong>Publish All</strong>.
    </div>
    @endif

    <form action="{{ route('teacher.sections.grades.save', $section) }}" method="POST">
        @csrf
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th style="width: 15%;">Student Number</th>
                        <th style="width: 25%;">Student Name</th>
                        <th class="text-center" style="width: 12%;">Midterm</th>
                        <th class="text-center" style="width: 12%;">Final</th>
                        <th class="text-center" style="width: 12%;">Status</th>
                        <th style="width: 24%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                            @if($section->grades_published)
                                <span class="text-muted">{{ $grade->remarks ?? '-' }}</span>
                            @else
                                <input type="text" name="grades[{{ $enrollment->student->id }}][remarks]" 
                                       class="form-control form-control-sm" value="{{ $grade->remarks ?? '' }}"
                                       placeholder="e.g., Passed">
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if(!$section->grades_published)
            <div class="d-flex justify-content-between align-items-center mt-4 border-top pt-3">
                <p class="text-muted small mb-0"><i class="fas fa-info-circle text-info"></i> Grades range from 1.0 (Highest) to 5.0 (Failed).</p>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import me-1"></i> Import CSV
                    </button>
                    <button type="submit" class="btn-outline-navy">
                        <i class="fas fa-save me-1"></i> Save Draft
                    </button>
                    <!-- Trigger Publish Modal -->
                    <button type="button" class="btn-navy" data-bs-toggle="modal" data-bs-target="#publishModal">
                        <i class="fas fa-paper-plane me-1"></i> Publish Grades
                    </button>
                </div>
            </div>
        @endif
    </form>
</div>

<!-- Publish Modal -->
@if(!$section->grades_published)
<div class="modal fade" id="publishModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-exclamation-triangle text-warning me-2"></i> Publish Final Grades?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to publish these grades? Once published, they will become <strong>permanently locked</strong> and visible to the students. You will no longer be able to modify them.</p>
                <p class="small text-muted">Make sure to 'Save Draft' first if you have pending changes on the screen.</p>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('teacher.sections.grades.publish', $section) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-navy"><i class="fas fa-lock me-1"></i> Confirm Publish</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Import CSV Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none; border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Grades (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teacher.sections.grades.import', $section) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Upload a CSV file with the following columns in exactly this order:
                        <br><strong>student_number, midterm, final, remarks</strong>
                    </p>
                    <input type="file" name="grades_csv" class="form-control" accept=".csv" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-navy"><i class="fas fa-upload me-1"></i> Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
