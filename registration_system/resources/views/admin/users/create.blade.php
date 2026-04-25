@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-user-plus me-2" style="color:var(--navy);"></i> Create Admin Account</h1>
        <p class="page-subtitle">Add a new administrator with full system access</p>
    </div>
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-navy bg-transparent text-secondary border">
            <i class="fas fa-arrow-left me-2"></i> Back to Admin List
        </a>
    </div>
</div>

<div class="glass-card p-0" style="max-width: 700px; margin: 0 auto; overflow: hidden;" data-aos="fade-up">
    <div class="p-4" style="background: var(--navy); color: white;">
        <h5 class="mb-0 text-center fw-bold">NEW ADMINISTRATOR ACCOUNT</h5>
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

        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Full Name</label>
                        <input type="text" name="name" class="form-modern-input" value="{{ old('name') }}" placeholder="e.g. Juan Dela Cruz" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Email Address</label>
                        <input type="email" name="email" class="form-modern-input" value="{{ old('email') }}" placeholder="admin@parsu.edu.ph" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Password</label>
                        <input type="password" name="password" class="form-modern-input" placeholder="Minimum 8 characters" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-modern-group">
                        <label class="form-modern-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-modern-input" placeholder="Re-enter password" required>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-navy bg-transparent text-secondary border">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-navy">
                            <i class="fas fa-user-plus me-2"></i> Create Admin Account
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
