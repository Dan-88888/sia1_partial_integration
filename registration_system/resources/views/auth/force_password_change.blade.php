@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="glass-card p-4" data-aos="fade-up">
            <div class="text-center mb-4">
                <div style="width:80px; height:80px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius:20px; display:flex; align-items:center; justify-content:center; font-size:2rem; color:#fff; margin: 0 auto 20px;">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3 class="fw-bold">Password Change Required</h3>
                <p class="text-muted">For your security, you must set a new password before continuing. This is required on your first login.</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('password.force_change.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Current Password</label>
                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required placeholder="Enter the password you logged in with">
                    @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">New Password</label>
                    <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required placeholder="At least 8 characters">
                    @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Repeat your new password">
                </div>

                <button type="submit" class="btn btn-gold w-100 mt-3">
                    <i class="fas fa-key me-2"></i> Set New Password & Continue
                </button>
            </form>

            <div class="text-center mt-4 d-flex justify-content-center gap-3">
                <form action="{{ route('password.force_change.skip') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none text-navy small">Skip for now</button>
                </form>
                
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none small">Or sign out</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
