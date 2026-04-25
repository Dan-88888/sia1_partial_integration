@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-file-invoice me-2" style="color:var(--navy);"></i> Enrollment Data Configuration</h1>
        <p class="page-subtitle">Configure academic status for <strong>{{ $student->user->name }}</strong> ({{ $student->student_number }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn-outline-navy">
            <i class="fas fa-arrow-left me-2"></i> Back to Student List
        </a>
    </div>
</div>

<div class="glass-card p-0" style="max-width: 900px; margin: 0 auto; overflow: hidden;" data-aos="fade-up">
    <!-- Header mirroring the ParSU Modal -->
    <div class="p-4" style="background: #9333ea; color: white;">
        <h5 class="mb-0 text-center fw-bold">ENROLLMENT DATA ({{ strtoupper($student->user->name) }})</h5>
    </div>

    <div class="p-5 bg-white">
        <form action="{{ route('admin.students.enrollment_data.update', $student->id) }}" method="POST">
            @csrf
            
            <div class="row g-4">
                <!-- Group 1: General Info -->
                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Course</label>
                        <input type="text" class="form-modern-input bg-light" value="{{ $student->course->course_code ?? 'N/A' }}" readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Department / Level</label>
                        <input type="text" class="form-modern-input bg-light" value="College" readonly>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Year Level</label>
                        <select name="year_level" class="form-modern-input" required>
                            @for($i=1; $i<=5; $i++)
                                <option value="{{ $i }}" {{ ($enrollmentData->year_level == $i) ? 'selected' : '' }}>Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Status Classification</label>
                        <select name="status" class="form-modern-input" required>
                            @php
                                $classifications = [
                                    'Regular', 'New Student', 'Transferee', 'Returnee', 'Cross Enrollee', 
                                    'Shifter', 'Foreigner', 'Special', 'Graduating'
                                ];
                            @endphp
                            @foreach($classifications as $class)
                                <option value="{{ $class }}" {{ ($enrollmentData->status == $class) ? 'selected' : '' }}>{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12"><hr class="my-3"></div>

                <!-- Group 2: Unit Caps & Rules -->
                <div class="col-md-4">
                    <div class="form-modern-group">
                        <label class="form-modern-label text-primary">Max Units / Period</label>
                        <input type="number" name="max_units" class="form-modern-input fw-bold" value="{{ $enrollmentData->max_units ?? 25 }}" required>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Payment Plan</label>
                        <input type="text" name="payment_plan" class="form-modern-input" value="{{ $enrollmentData->payment_plan }}" placeholder="e.g. Regular Cash / Installment">
                    </div>
                </div>

                <!-- Checkboxes -->
                <div class="col-12 mt-4 bg-light p-4 rounded-3 border">
                    <h6 class="fw-bold text-navy mb-3">Validation Rules</h6>
                    <div class="d-flex gap-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="check_prerequisites" id="chkPre" value="1" {{ ($enrollmentData->check_prerequisites) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="chkPre">
                                Strict Check Pre-requisites
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="check_enrollment_count" id="chkCount" value="1" checked disabled>
                            <label class="form-check-label fw-bold" for="chkCount">
                                Check Enrollment Count (Capacity)
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-5">
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-navy bg-transparent text-secondary border">
                            Cancel Changes
                        </a>
                        <button type="submit" class="btn btn-navy hvr-grow">
                            <i class="fas fa-save me-2"></i> Save Enrollment Configuration
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
