@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="w-100">
        <h1 class="page-title"><i class="fas fa-cogs me-2" style="color:var(--navy);"></i> Global System Settings</h1>
        <p class="page-subtitle">Configure the active academic year, semester, and branding</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8" data-aos="fade-up">
        <div class="glass-card">
            <div class="card-header-modern">
                <i class="fas fa-sliders-h"></i>
                <h4>Configuration Panel</h4>
            </div>

            <form action="{{ route('admin.settings.save') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-modern-group">
                            <label class="form-modern-label">Active Semester</label>
                            <select name="settings[active_semester]" class="form-modern-input">
                                <option value="1" {{ \App\Models\Setting::getValue('active_semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                                <option value="2" {{ \App\Models\Setting::getValue('active_semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
                                <option value="Summer" {{ \App\Models\Setting::getValue('active_semester') == 'Summer' ? 'selected' : '' }}>Summer</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-modern-group">
                            <label class="form-modern-label">Active School Year</label>
                            <input type="text" name="settings[active_school_year]" class="form-modern-input" placeholder="e.g. 2024-2025" 
                                   value="{{ \App\Models\Setting::getValue('active_school_year') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-modern-group">
                            <label class="form-modern-label">Enrollment Start Date</label>
                            <input type="date" name="settings[enrollment_start]" class="form-modern-input" 
                                   value="{{ \App\Models\Setting::getValue('enrollment_start') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-modern-group">
                            <label class="form-modern-label">Enrollment End Date</label>
                            <input type="date" name="settings[enrollment_end]" class="form-modern-input" 
                                   value="{{ \App\Models\Setting::getValue('enrollment_end') }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-modern-group">
                            <label class="form-modern-label">Max Units per Student</label>
                            <input type="number" name="settings[max_units]" class="form-modern-input" 
                                   value="{{ \App\Models\Setting::getValue('max_units', 24) }}">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-modern-group">
                            <label class="form-modern-label">University Name</label>
                            <input type="text" name="settings[university_name]" class="form-modern-input" 
                                   value="{{ \App\Models\Setting::getValue('university_name', 'Partido State University') }}">
                        </div>
                    </div>
                </div>

                <div class="mt-5 p-3" style="background:rgba(255,215,0,0.05); border-radius:12px; border:1px dashed var(--gold);">
                    <div class="d-flex gap-3 align-items-center">
                        <i class="fas fa-info-circle" style="color:var(--gold); font-size:1.5rem;"></i>
                        <p class="mb-0" style="font-size:0.85rem; color:var(--text-secondary);">
                            Changes made here will take effect immediately across all user portals, including registration headers and COR generation.
                        </p>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-navy px-5">
                        <i class="fas fa-save me-2"></i> Save System Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4" data-aos="fade-left">
        <div class="glass-card">
            <div class="card-header-modern">
                <i class="fas fa-history"></i>
                <h4>System Info</h4>
            </div>
            <ul class="list-unstyled mb-0" style="display:flex; flex-direction:column; gap:15px;">
                <li class="d-flex justify-content-between">
                    <span class="text-muted">Laravel Version</span>
                    <span class="fw-bold">{{ App::version() }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span class="text-muted">PHP Version</span>
                    <span class="fw-bold">{{ PHP_VERSION }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span class="text-muted">Environment</span>
                    <span class="badge bg-success">Production-Mock</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span class="text-muted">Last Update</span>
                    <span class="fw-bold">{{ date('M d, Y') }}</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
