@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-user-edit me-2" style="color:var(--navy);"></i> Edit Student Profile</h1>
        <p class="page-subtitle">Modify profile information for <strong>{{ $student->user->name }}</strong> ({{ $student->student_number }})</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.students.index') }}" class="btn btn-navy bg-transparent text-secondary border">
            <i class="fas fa-arrow-left me-2"></i> Back to Student List
        </a>
    </div>
</div>

<div class="glass-card p-0" style="max-width: 900px; margin: 0 auto; overflow: hidden;" data-aos="fade-up">
    <div class="p-4" style="background: var(--navy); color: white;">
        <h5 class="mb-0 text-center fw-bold">STUDENT PROFILE — {{ strtoupper($student->user->name) }}</h5>
    </div>

    <div class="p-5 bg-white">
        @if($errors->any())
            <div class="alert-modern alert-danger alert mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('admin.students.update', $student->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Personal Information -->
                <div class="col-12">
                    <h6 class="fw-bold text-navy mb-0"><i class="fas fa-user me-2"></i>Personal Information</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Full Name</label>
                        <input type="text" name="name" class="form-modern-input" value="{{ old('name', $student->user->name) }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Email Address</label>
                        <input type="email" name="email" class="form-modern-input" value="{{ old('email', $student->user->email) }}" required>
                    </div>
                </div>

                <div class="col-12"><hr class="my-1"></div>

                <!-- Academic Information -->
                <div class="col-12">
                    <h6 class="fw-bold text-navy mb-0"><i class="fas fa-graduation-cap me-2"></i>Academic Information</h6>
                </div>

                <div class="col-md-4">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Student Number</label>
                        <input type="text" class="form-modern-input bg-light" value="{{ $student->student_number }}" readonly>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Course / Program</label>
                        <select name="course" class="form-modern-input" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->course_code }}" {{ old('course', $student->course) == $course->course_code ? 'selected' : '' }}>
                                    {{ $course->course_code }} — {{ $course->course_name }}
                                </option>
                            @endforeach
                            @if(!$courses->contains('course_code', $student->course))
                                <option value="{{ $student->course }}" selected>{{ $student->course }}</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Year Level</label>
                        <select name="year_level" class="form-modern-input" required>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('year_level', $student->year_level) == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="col-12"><hr class="my-1"></div>

                <!-- Admission Status -->
                <div class="col-12">
                    <h6 class="fw-bold text-navy mb-0"><i class="fas fa-shield-alt me-2"></i>Admission Status</h6>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Status</label>
                        <select name="admission_status" class="form-modern-input" required>
                            <option value="admitted" {{ old('admission_status', $student->admission_status) == 'admitted' ? 'selected' : '' }}>✅ Admitted</option>
                            <option value="pending" {{ old('admission_status', $student->admission_status) == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="rejected" {{ old('admission_status', $student->admission_status) == 'rejected' ? 'selected' : '' }}>❌ Rejected</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Admission Date</label>
                        <input type="text" class="form-modern-input bg-light" value="{{ $student->admission_date ? $student->admission_date->format('M d, Y') : 'N/A' }}" readonly>
                    </div>
                </div>

                <!-- Actions -->
                <div class="col-12 mt-4">
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.students.index') }}" class="btn btn-navy bg-transparent text-secondary border">
                            Cancel Changes
                        </a>
                        <button type="submit" class="btn btn-navy">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
