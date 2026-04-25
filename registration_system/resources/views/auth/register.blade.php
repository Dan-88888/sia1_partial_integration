@extends('layouts.app')

@section('content')
<div class="glass-card" style="padding: 2.5rem;">
    <!-- Header -->
    <div class="text-center mb-4">
        <div style="width:60px;height:60px;border-radius:16px;background:rgba(255,215,0,0.12);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
            <i class="fas fa-user-plus" style="font-size:1.4rem;color:var(--gold);"></i>
        </div>
        <h2 style="font-weight:800;font-size:1.5rem;margin-bottom:0.3rem;">Create Account</h2>
        <p style="color:var(--text-muted);font-size:0.9rem;">Register to access the student portal</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Full Name -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fas fa-user me-1" style="color:var(--gold);font-size:0.8rem;"></i> Full Name
            </label>
            <input id="name" type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   name="name" value="{{ old('name') }}" 
                   required autocomplete="name" autofocus
                   placeholder="Juan Dela Cruz">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1" style="color:var(--gold);font-size:0.8rem;"></i> Email Address
            </label>
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" 
                   required autocomplete="email"
                   placeholder="your.email@university.edu">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-1" style="color:var(--gold);font-size:0.8rem;"></i> Password
            </label>
            <div class="position-relative">
                <input id="password" type="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       name="password" required autocomplete="new-password"
                       placeholder="Minimum 8 characters">
                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y" 
                        onclick="togglePassword('password', 'toggleIcon1')" style="color:var(--text-muted);border:none;padding-right:12px;">
                    <i class="fas fa-eye" id="toggleIcon1"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
            <!-- Password Strength Indicator -->
            <div class="mt-2">
                <div class="progress-modern">
                    <div class="progress-bar" id="strengthBar" style="width:0%;"></div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small style="color:var(--text-muted);font-size:0.7rem;" id="strengthText">Password strength</small>
                    <small style="color:var(--text-muted);font-size:0.7rem;" id="strengthLevel"></small>
                </div>
            </div>
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password-confirm" class="form-label">
                <i class="fas fa-shield-alt me-1" style="color:var(--gold);font-size:0.8rem;"></i> Confirm Password
            </label>
            <div class="position-relative">
                <input id="password-confirm" type="password" class="form-control" 
                       name="password_confirmation" required autocomplete="new-password"
                       placeholder="Re-enter your password">
                <button type="button" class="btn position-absolute end-0 top-50 translate-middle-y" 
                        onclick="togglePassword('password-confirm', 'toggleIcon2')" style="color:var(--text-muted);border:none;padding-right:12px;">
                    <i class="fas fa-eye" id="toggleIcon2"></i>
                </button>
            </div>
        </div>

        <!-- Info note -->
        <div class="mb-4" style="background:rgba(0,207,232,0.08);border:1px solid rgba(0,207,232,0.15);border-radius:10px;padding:0.75rem 1rem;">
            <small style="color:#00cfe8;">
                <i class="fas fa-info-circle me-1"></i>
                If you have been admitted, your admission record will be linked automatically using your email.
            </small>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-gold w-100" style="padding:0.8rem;">
            <i class="fas fa-user-plus me-2"></i> Create Account
        </button>
    </form>

    <!-- Divider -->
    <div class="text-center my-4" style="position:relative;">
        <hr style="border-color:var(--glass-border);">
        <span style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);background:rgba(17,22,57,0.95);padding:0 1rem;color:var(--text-muted);font-size:0.8rem;">
            Already registered?
        </span>
    </div>

    <!-- Login Link -->
    <a href="{{ route('login') }}" class="btn btn-outline-gold w-100">
        <i class="fas fa-sign-in-alt me-2"></i> Sign In
    </a>
</div>

@push('scripts')
<script>
    function togglePassword(fieldId, iconId) {
        const pwd = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        if (pwd.type === 'password') {
            pwd.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            pwd.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Password Strength Meter
    document.getElementById('password').addEventListener('input', function() {
        const val = this.value;
        let strength = 0;
        if (val.length >= 8) strength++;
        if (val.length >= 12) strength++;
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^a-zA-Z0-9]/.test(val)) strength++;

        const bar = document.getElementById('strengthBar');
        const level = document.getElementById('strengthLevel');
        const colors = ['#ea5455', '#ff9f43', '#ff9f43', '#28c76f', '#28c76f'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
        const widths = ['20%', '40%', '60%', '80%', '100%'];

        if (val.length === 0) {
            bar.style.width = '0%';
            level.textContent = '';
        } else {
            const idx = Math.min(strength, 4);
            bar.style.width = widths[idx];
            bar.style.background = colors[idx];
            level.textContent = labels[idx];
            level.style.color = colors[idx];
        }
    });
</script>
@endpush
@endsection