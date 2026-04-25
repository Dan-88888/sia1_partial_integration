@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-user-circle me-2" style="color:var(--gold);"></i> Account Settings</h1>
        <p class="page-subtitle">Manage your personal information and security</p>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Info -->
    <div class="col-lg-7" data-aos="fade-right">
        <div class="glass-card h-100">
            <div class="card-header-modern">
                <i class="fas fa-id-card"></i>
                <h4>Profile Information</h4>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                @if($user->student)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Degree Course</label>
                        <input type="text" name="course" class="form-control" value="{{ old('course', $user->student->course) }}" readonly style="opacity:0.7 cursor:not-allowed;">
                        <small class="text-muted" style="font-size:0.7rem;">Contact Registrar to change course.</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Year Level</label>
                        <input type="number" name="year_level" class="form-control" value="{{ old('year_level', $user->student->year_level) }}" readonly style="opacity:0.7 cursor:not-allowed;">
                    </div>
                </div>
                @endif

                @if($user->teacher)
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror" value="{{ old('contact', $user->contact) }}">
                        @error('contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Department ID</label>
                        <input type="text" name="department_id" class="form-control @error('department_id') is-invalid @enderror" value="{{ old('department_id', $user->teacher->department_id) }}">
                        @error('department_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                @endif
                
                <hr class="my-4" style="border-color:var(--glass-border);">
                
                <div class="card-header-modern">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Security Update</h4>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password to make changes">
                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" placeholder="8+ characters">
                        @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Repeat new password">
                    </div>
                </div>
                
                <div class="mt-4">
                    <button type="submit" class="btn btn-gold w-100">
                        <i class="fas fa-save me-2"></i> Update Account Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Account Overview -->
    <div class="col-lg-5" data-aos="fade-left">
        <div class="glass-card">
            <div class="card-header-modern">
                <i class="fas fa-info-circle"></i>
                <h4>Account Overview</h4>
            </div>
            
            <div class="d-flex align-items-center gap-3 mb-4 p-3" style="background:rgba(255,255,255,0.03); border-radius:12px;">
                <div style="width:60px; height:60px; background:var(--accent-gradient); border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; color:var(--navy);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h5 class="mb-0" style="font-weight:700;">{{ $user->name }}</h5>
                    <div class="text-muted" style="font-size:0.8rem;">{{ ucfirst($user->role) }} Account</div>
                </div>
            </div>
            
            <ul class="list-unstyled mb-0" style="display:flex; flex-direction:column; gap:12px;">
                <li class="d-flex justify-content-between align-items-center p-2" style="border-bottom:1px solid var(--glass-border);">
                    <span class="text-muted">Account ID</span>
                    <span class="fw-bold">{{ $user->id }}</span>
                </li>
                <li class="d-flex justify-content-between align-items-center p-2" style="border-bottom:1px solid var(--glass-border);">
                    <span class="text-muted">Member Since</span>
                    <span class="fw-bold">{{ $user->created_at->format('M d, Y') }}</span>
                </li>
                @if($user->student)
                <li class="d-flex justify-content-between align-items-center p-2">
                    <span class="text-muted">Admission Status</span>
                    <span class="status-badge {{ $user->student->admission_status === 'admitted' ? 'success' : 'warning' }}">
                        {{ strtoupper($user->student->admission_status) }}
                    </span>
                </li>
                @endif
            </ul>
        </div>
        
        <div class="glass-card mt-4" style="border-left:4px solid var(--gold);">
            <div class="d-flex gap-3">
                <i class="fas fa-lightbulb" style="color:var(--gold); font-size:1.5rem;"></i>
                <div>
                    <h6 class="mb-1" style="font-weight:700;">Security Tip</h6>
                    <p class="mb-0 text-muted" style="font-size:0.8rem;">Always use a strong, unique password for your university portal account. Avoid sharing your credentials with anyone.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
