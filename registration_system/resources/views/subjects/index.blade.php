@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-calendar-alt me-2" style="color:var(--gold);"></i> Class Schedule & Enrollment</h1>
        <p class="page-subtitle">Available sections and schedules for this semester</p>
    </div>
    <a href="{{ route('enrollments.index') }}" class="btn btn-outline-gold btn-sm">
        <i class="fas fa-clipboard-list me-1"></i> My Enrollments
    </a>
</div>

<!-- Search & Filter Card -->
<div class="glass-card mb-4" data-aos="fade-up">
    <form method="GET" action="{{ route('subjects.index') }}">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label"><i class="fas fa-search me-1" style="color:var(--gold);font-size:0.75rem;"></i> Search</label>
                <input type="text" name="search" placeholder="Subject name or code..." 
                       value="{{ request('search') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="fas fa-graduation-cap me-1" style="color:var(--gold);font-size:0.75rem;"></i> Course</label>
                <select name="course" class="form-select">
                    <option value="">All Courses</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label"><i class="fas fa-calendar-day me-1" style="color:var(--gold);font-size:0.75rem;"></i> Day</label>
                <select name="day" class="form-select">
                    <option value="">All Days</option>
                    @foreach(['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $day)
                        <option value="{{ $day }}" {{ request('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-gold w-100">
                    <i class="fas fa-filter me-1"></i> Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Sections Table -->
<div class="glass-card" data-aos="fade-up" data-aos-delay="100">
    <div class="card-header-modern">
        <i class="fas fa-list"></i>
        <h4>Offered Sections</h4>
        <span class="ms-auto" style="color:var(--text-muted);font-size:0.85rem;">
            {{ $sections->total() }} sections found
        </span>
    </div>

    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>Instructor</th>
                    <th>Schedule</th>
                    <th>Room</th>
                    <th>Units</th>
                    <th>Slots</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sections as $section)
                    @php
                        $enrolledCount = $section->enrollments()->where('status', 'enrolled')->count();
                        $available = $section->capacity - $enrolledCount;
                        $percentFull = $section->capacity > 0 ? ($enrolledCount / $section->capacity) * 100 : 100;
                    @endphp
                    <tr>
                        <td>
                            <span style="color:var(--gold);font-weight:600;">{{ $section->section_name }}</span>
                        </td>
                        <td style="color:var(--text-primary);font-weight:500;">
                            {{ $section->subject->subject_name }}
                            <div style="font-size:0.75rem;color:var(--text-muted);">{{ $section->subject->subject_code }}</div>
                        </td>
                        <td>
                            <div style="font-size:0.85rem;">
                                <i class="fas fa-user-tie me-1" style="color:var(--gold);opacity:0.7;"></i>
                                {{ $section->teacher->user->name ?? 'TBA' }}
                            </div>
                        </td>
                        <td>
                            <span class="status-badge info">{{ $section->day }}</span>
                            <div style="font-size:0.8rem;margin-top:4px;">
                                {{ date('h:i A', strtotime($section->start_time)) }} - {{ date('h:i A', strtotime($section->end_time)) }}
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-door-open me-1" style="color:var(--gold);font-size:0.7rem;"></i>
                            {{ $section->room->name ?? 'TBA' }}
                        </td>
                        <td style="text-align:center;">
                            <span style="font-weight:700;color:var(--text-primary);">{{ $section->subject->units }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress-modern flex-grow-1" style="width:60px;">
                                    <div class="progress-bar {{ $percentFull >= 90 ? 'danger' : '' }}" 
                                         style="width:{{ $percentFull }}%;"></div>
                                </div>
                                <span style="font-size:0.8rem;font-weight:600;color:{{ $available > 0 ? '#28c76f' : '#ea5455' }};">
                                    {{ $available }}/{{ $section->capacity }}
                                </span>
                            </div>
                        </td>
                        <td>
                            @if(in_array($section->id, $enrolledSectionIds))
                                <span class="status-badge success">
                                    <i class="fas fa-check"></i> Enrolled
                                </span>
                            @else
                                <form action="{{ route('enrollments.enroll', $section) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success-modern btn-sm" 
                                            {{ $available <= 0 ? 'disabled' : '' }}
                                            onclick="return confirm('Enroll in {{ $section->subject->subject_name }} ({{ $section->section_name }})?')">
                                        <i class="fas fa-plus me-1"></i> Enroll
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding:3rem;color:var(--text-muted);">
                            <i class="fas fa-search" style="font-size:2rem;display:block;margin-bottom:0.5rem;opacity:0.3;"></i>
                            No sections found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($sections->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $sections->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection