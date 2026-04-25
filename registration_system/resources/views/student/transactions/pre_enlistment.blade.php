@extends('layouts.app')

@section('content')
<div class="section-header" data-aos="fade-down">
    <div>
        <h2 class="page-title">Pre-Enlistment</h2>
        <p class="page-subtitle">Select subjects you intent to take for {{ $activeSY }}, Semester {{ $activeSemester }}</p>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-navy" style="background: #4A90E2;" data-bs-toggle="modal" data-bs-target="#enrollmentDataModal">
            <i class="fas fa-info-circle me-2"></i> Enrollment Data
        </button>
        <a href="{{ route('student.transactions.enrollment') }}" class="btn-navy">
            <i class="fas fa-arrow-right me-2"></i> Proceed to Enrollment
        </a>
    </div>
</div>

<div class="row g-4" data-aos="fade-up" data-aos-delay="100">
    <div class="col-lg-8">
        <div class="glass-card p-4">
            <div class="card-header-modern mb-4">
                <i class="fas fa-book"></i>
                <h3>Available Subjects</h3>
            </div>
            
            <div class="table-responsive">
                <table class="table-modern w-100">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Subject Name</th>
                            <th>Units</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                        <tr>
                            <td class="fw-bold">{{ $subject->subject_code }}</td>
                            <td>{{ $subject->subject_name }}</td>
                            <td>{{ $subject->units }} Units</td>
                            <td class="text-center">
                                @if(in_array($subject->id, $preEnlistedIds))
                                    <button class="btn-danger-modern btn-sm remove-btn" data-id="{{ $subject->id }}">
                                        <i class="fas fa-minus-circle"></i> Remove
                                    </button>
                                @else
                                    <button class="btn-success-modern btn-sm add-btn" data-id="{{ $subject->id }}">
                                        <i class="fas fa-plus-circle"></i> Add
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card p-4 sticky-top" style="top: 120px;">
            <div class="card-header-modern mb-4">
                <i class="fas fa-list-check"></i>
                <h3>Selection Summary</h3>
            </div>
            
            <div id="selection-summary">
                <div class="stat-card mb-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label">Total Units</div>
                            <div class="stat-value" id="total-units">0</div>
                        </div>
                        <div class="stat-icon gold">
                            <i class="fas fa-layer-group"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card mb-3 p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="stat-label">Selected Subjects</div>
                            <div class="stat-value" id="total-subjects">0</div>
                        </div>
                        <div class="stat-icon blue">
                            <i class="fas fa-book-open"></i>
                        </div>
                    </div>
                </div>

                <div class="alert-modern alert-info mb-0">
                    <i class="fas fa-info-circle"></i>
                    <div>Pre-enlisting helps the university plan section capacities.</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const updateSummary = () => {
            const rows = document.querySelectorAll('tbody tr');
            let units = 0;
            let count = 0;
            rows.forEach(row => {
                if (row.querySelector('.remove-btn')) {
                    const unitText = row.cells[2].textContent;
                    units += parseInt(unitText);
                    count++;
                }
            });
            document.getElementById('total-units').textContent = units;
            document.getElementById('total-subjects').textContent = count;
        };

        updateSummary();

        document.querySelectorAll('.add-btn, .remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const isAdd = this.classList.contains('add-btn');
                const subjectId = this.dataset.id;
                const url = isAdd ? "{{ route('student.transactions.pre_enlistment.add') }}" : "{{ route('student.transactions.pre_enlistment.remove') }}";
                
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ subject_id: subjectId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (isAdd) {
                            this.classList.remove('btn-success-modern', 'add-btn');
                            this.classList.add('btn-danger-modern', 'remove-btn');
                            this.innerHTML = '<i class="fas fa-minus-circle"></i> Remove';
                        } else {
                            this.classList.remove('btn-danger-modern', 'remove-btn');
                            this.classList.add('btn-success-modern', 'add-btn');
                            this.innerHTML = '<i class="fas fa-plus-circle"></i> Add';
                        }
                        updateSummary();
                    } else {
                        alert(data.message || 'Failed to update pre-enlistment.');
                    }
                })
                .catch(err => {
                    alert('An error occurred while connecting to the server.');
                });
            });
        });
    });
</script>
@endpush
@push('modals')
@include('student.partials.enrollment_data_modal')
@endpush
@endsection
