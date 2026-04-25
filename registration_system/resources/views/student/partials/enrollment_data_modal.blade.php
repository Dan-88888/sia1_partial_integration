<!-- Enrollment Data Modal -->
<div class="modal fade" id="enrollmentDataModal" tabindex="-1" aria-labelledby="enrollmentDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; overflow: hidden;">
            <div class="modal-header" style="background: #9333ea; color: white; border-bottom: none;">
                <h5 class="modal-title w-100 text-center" id="enrollmentDataModalLabel">
                    Enrollment Data ({{ Auth::user()->name }})
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('student.transactions.enrollment_data.update') }}" method="POST">
                @csrf
                <div class="modal-body p-4" style="background: #f8fafc;">
                    <div class="row g-3">
                        <!-- Course & Curriculum Row -->
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">Course Code</label>
                            <input type="text" name="course_code" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->course_code ?? ($student->course->course_code ?? '') }}" placeholder="e.g. BSIT">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">Dept</label>
                            <input type="text" name="dept" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->dept ?? ($student->department->name ?? 'N/A') }}">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">Curriculum</label>
                            <input type="text" name="curriculum" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->curriculum ?? 'BSIT2023' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small mb-1">Level</label>
                            <div class="form-control bg-white border-0 py-2">College</div>
                        </div>

                        <!-- Section Info -->
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">Section No</label>
                            <input type="text" name="section_no" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->section_no ?? '0' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">Section Name</label>
                            <input type="text" name="section_name" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->section_name ?? 'N/A' }}">
                        </div>

                        <div class="col-12"><hr class="my-3 opacity-10"></div>

                        <!-- Year & TF Level -->
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">Year Level</label>
                            <select name="year_level" class="form-select border-0 shadow-sm" required>
                                @for($i=1; $i<=5; $i++)
                                    <option value="{{ $i }}" {{ ($enrollmentData->year_level ?? 1) == $i ? 'selected' : '' }}>Year {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-navy small fw-bold mb-1">TF Level</label>
                            <input type="text" name="tf_level" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->tf_level ?? '0' }}">
                        </div>

                        <!-- Class Codes / Checkboxes -->
                        <div class="col-12 mt-4">
                            <label class="form-label text-navy small fw-bold mb-2">Enrollment Status</label>
                            <div class="row">
                                @php
                                    $currentStatus = $enrollmentData->status ?? 'Regular';
                                    $classifications = [
                                        'Regular', 'New Student', 'Transferee', 'Returnee', 'Cross Enrollee', 
                                        'Shifter', 'Foreigner', 'Special', 'Graduating'
                                    ];
                                @endphp
                                @foreach($classifications as $class)
                                <div class="col-md-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" value="{{ $class }}" id="part_st{{ $loop->index }}" {{ $currentStatus == $class ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="part_st{{ $loop->index }}">{{ $class }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-12"><hr class="my-3 opacity-10"></div>

                        <!-- Payment & Units -->
                        <div class="col-md-4">
                            <label class="form-label text-navy small fw-bold mb-1">Payment Plan</label>
                            <input type="text" name="payment_plan" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->payment_plan ?? '' }}" placeholder="e.g. Regular">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-navy small fw-bold mb-1">Late Enrollee (Days)</label>
                            <input type="number" name="late_enrollee_days" class="form-control border-0 shadow-sm" value="{{ $enrollmentData->late_enrollee_days ?? 0 }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-navy small fw-bold mb-1 text-primary">Max Units</label>
                            <input type="number" name="max_units" class="form-control border-0 shadow-sm fw-bold text-primary" value="{{ $enrollmentData->max_units ?? 25 }}">
                        </div>

                        <div class="col-12 mt-3 p-3 rounded" style="background: rgba(147, 51, 234, 0.05);">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="check_prerequisites" id="chk_pre" {{ ($enrollmentData->check_prerequisites ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold" for="chk_pre">Check pre-requisites (Required)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="check_enrollment_count" id="chk_count" {{ ($enrollmentData->check_enrollment_count ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold" for="chk_count">Check enrollment count (Required)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 justify-content-center pb-4" style="background: #f8fafc;">
                    <button type="submit" class="btn btn-primary px-5 py-2 shadow-sm" style="background: #9333ea; border: none;">
                        <i class="fas fa-save me-2"></i> Save Enrollment Data
                    </button>
                    <button type="button" class="btn btn-outline-secondary px-5 py-2" data-bs-dismiss="modal">CANCEL</button>
                </div>
            </form>
        </div>
    </div>
</div>
