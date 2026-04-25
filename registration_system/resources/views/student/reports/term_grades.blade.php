@extends('layouts.app')

@section('content')
<div class="section-header">
    <div>
        <h2 class="page-title">Academic Grades</h2>
        <p class="page-subtitle">View your performance for {{ $activeSY }}, Semester {{ $activeSemester }}</p>
    </div>
    <div class="d-flex gap-2">
        <form action="{{ route('student.reports.term_grades') }}" method="GET" class="d-flex gap-2 align-items-center">
            <select name="school_year" class="form-modern-input py-2" style="width: auto;" onchange="this.form.submit()">
                @foreach($periods->unique('school_year') as $period)
                    <option value="{{ $period->school_year }}" {{ $activeSY == $period->school_year ? 'selected' : '' }}>{{ $period->school_year }}</option>
                @endforeach
            </select>
            <select name="semester" class="form-modern-input py-2" style="width: auto;" onchange="this.form.submit()">
                <option value="1" {{ $activeSemester == '1' ? 'selected' : '' }}>1st Semester</option>
                <option value="2" {{ $activeSemester == '2' ? 'selected' : '' }}>2nd Semester</option>
            </select>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Semester GPA</div>
                    <div class="stat-value text-navy">
                        @php
                            $totalWeighted = $grades->sum(fn($g) => $g->final_grade * ($g->enrollment->section->subject->units ?? 0));
                            $totalUnits = $grades->sum(fn($g) => $g->enrollment->section->subject->units ?? 0);
                            $gpa = $totalUnits > 0 ? number_format($totalWeighted / $totalUnits, 2) : '0.00';
                        @endphp
                        {{ $gpa }}
                    </div>
                </div>
                <div class="stat-icon gold">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Units Completed</div>
                    <div class="stat-value text-navy">{{ $grades->where('final_grade', '>=', 75)->sum(fn($g) => $g->enrollment->section->subject->units ?? 0) }}</div>
                </div>
                <div class="stat-icon blue">
                    <i class="fas fa-graduation-cap"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="glass-card p-4">
    <div class="card-header-modern mb-4">
        <i class="fas fa-list-ol"></i>
        <h3>Report of Grades</h3>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Subject Code</th>
                    <th>Subject Title</th>
                    <th class="text-center">Units</th>
                    <th class="text-center">Grade</th>
                    <th class="text-center">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                <tr>
                    <td class="fw-bold">{{ $grade->enrollment->section->subject->subject_code }}</td>
                    <td>{{ $grade->enrollment->section->subject->subject_name }}</td>
                    <td class="text-center">{{ $grade->enrollment->section->subject->units }}</td>
                    <td class="text-center fw-bold {{ $grade->final_grade >= 75 ? 'text-success' : 'text-danger' }}" style="font-size: 1.1rem;">
                        {{ number_format($grade->final_grade, 2) }}
                    </td>
                    <td class="text-center">
                        @if($grade->final_grade >= 75)
                            <span class="status-badge success">Passed</span>
                        @else
                            <span class="status-badge danger">Failed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        No grades posted for this term.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
