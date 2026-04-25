@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-door-open me-2" style="color:var(--navy);"></i> Facility Setup</h1>
            <p class="page-subtitle">Configure new classroom or laboratory space</p>
        </div>
        <a href="{{ route('admin.rooms.index') }}" class="btn btn-navy">
            <i class="fas fa-arrow-left me-2"></i> View All Rooms
        </a>
    </div>
</div>

<!-- Main Form Card -->
<div class="glass-card" style="max-width: 800px; margin: 0 auto;" data-aos="fade-up">
    <form action="{{ route('admin.rooms.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Room Identifier</label>
                    <input type="text" name="name" class="form-modern-input" placeholder="e.g. Rm 101" required>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Campus Building</label>
                    <input type="text" name="building" class="form-modern-input" placeholder="e.g. IT Building">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Floor Level</label>
                    <input type="text" name="floor" class="form-modern-input" placeholder="e.g. 1st Floor">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-modern-group">
                    <label class="form-modern-label">Seating Capacity</label>
                    <input type="number" name="capacity" class="form-modern-input" value="40" min="1" required>
                </div>
            </div>
        </div>

        <div class="mt-5 d-flex gap-3 justify-content-end">
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-navy" style="background:transparent; color:var(--text-secondary); border:1px solid var(--border);">
                Cancel
            </a>
            <button type="submit" class="btn btn-navy">
                <i class="fas fa-save me-2"></i> Register Facility
            </button>
        </div>
    </form>
</div>
@endsection
