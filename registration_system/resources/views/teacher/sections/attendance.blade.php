@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">Attendance Tracking</h2>
        <p class="page-subtitle">{{ $section->subject->subject_name }} ({{ $section->name ?? $section->section_name }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.dashboard') }}" class="btn-outline-navy">
            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="glass-card p-4" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header-modern mb-4">
        <div class="d-flex justify-content-between align-items-center w-100">
            <div>
                <i class="fas fa-users-viewfinder"></i>
                <h3 class="mb-0" style="display:inline-block; margin-left:10px;">Class List</h3>
            </div>
            
            <form id="dateForm" action="{{ route('teacher.sections.attendance', $section) }}" method="GET" class="d-flex align-items-center gap-2">
                <span class="text-muted small fw-bold">Date:</span>
                <input type="date" name="date" class="form-control form-control-sm" style="width:auto;" value="{{ $date }}" onchange="document.getElementById('dateForm').submit();">
            </form>
        </div>
    </div>

    <div class="card-header-modern mb-4 d-flex align-items-center">
        <i class="fas fa-clipboard-list" style="color:var(--navy);"></i>
        <h4 class="mb-0">Class Roster</h4>
        <div class="ms-auto">
            <a href="{{ route('teacher.sections.attendance.history', $section) }}" class="btn btn-sm btn-outline-navy">
                <i class="fas fa-history me-1"></i> View Attendance History
            </a>
        </div>
    </div>
    
    <form action="{{ route('teacher.sections.attendance.save', $section) }}" method="POST">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        
        <div class="table-responsive">
            <table class="table-modern w-100">
                <thead>
                    <tr>
                        <th>Student Number</th>
                        <th>Student Name</th>
                        <th class="text-center">Current Attendance %</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($enrollments as $enrollment)
                    <tr>
                        <td class="fw-bold">{{ $enrollment->student->student_number }}</td>
                        <td>{{ $enrollment->student->user->name }}</td>
                        <td class="text-center">
                            @php 
                                $pct = $enrollment->attendance_percentage;
                                $color = $pct >= 90 ? '#22c55e' : ($pct >= 75 ? '#3b82f6' : '#ef4444');
                            @endphp
                            <div class="fw-bold" style="color: {{ $color }};">{{ number_format($pct, 1) }}%</div>
                            <div class="progress mt-1" style="height: 4px; border-radius: 2px;">
                                <div class="progress-bar" style="width: {{ $pct }}%; background-color: {{ $color }};"></div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @php $currentStatus = $attendances[$enrollment->student_id]->status ?? ''; @endphp
                                <select name="status[{{ $enrollment->student_id }}]" class="form-modern-input py-1 px-2" style="font-size: 0.8rem; width: 120px;">
                                    <option value="Present" {{ $currentStatus == 'Present' ? 'selected' : '' }}>Present</option>
                                    <option value="Late" {{ $currentStatus == 'Late' ? 'selected' : '' }}>Late</option>
                                    <option value="Absent" {{ $currentStatus == 'Absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="Excused" {{ $currentStatus == 'Excused' ? 'selected' : '' }}>Excused</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="d-flex justify-content-end gap-2 mt-4 mt-4">
            <button type="button" class="btn btn-outline-secondary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="fas fa-file-import me-2"></i> Import CSV
            </button>
            <button type="submit" class="btn-navy">
                <i class="fas fa-save me-2"></i> Save Attendance
            </button>
        </div>
    </form>
</div>

<!-- Import CSV Modal -->
<div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border: none; border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Import Attendance (CSV)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teacher.sections.attendance.import', $section) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                <div class="modal-body">
                    <p class="small text-muted mb-3">
                        Upload a CSV file with the following columns in exactly this order:
                        <br><strong>student_number, status (Present/Late/Absent/Excused)</strong>
                    </p>
                    <input type="file" name="attendance_csv" class="form-control" accept=".csv" required>
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
