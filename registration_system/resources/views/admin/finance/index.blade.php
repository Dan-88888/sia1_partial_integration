@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="w-100">
        <h1 class="page-title"><i class="fas fa-file-invoice-dollar me-2" style="color:var(--navy);"></i> Billing & Accounting</h1>
        <p class="page-subtitle">Manage student tuition payments and billing records</p>
    </div>
</div>

<div class="glass-card" data-aos="fade-up">
    <div class="d-flex align-items-center mb-4 pb-3" style="border-bottom: 1px solid #f1f5f9;">
        <i class="fas fa-list me-2" style="color:var(--navy);"></i>
        <h4 class="mb-0" style="font-weight:700; font-size:1.1rem; color:var(--navy);">Billing Records</h4>
    </div>

    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Reference #</th>
                    <th>Amount</th>
                    <th>Semester/SY</th>
                    <th>Status</th>
                    <th>Date Paid</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $payment->student->user->name }}</div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">{{ $payment->student->student_number }}</div>
                    </td>
                    <td><code style="color:var(--gold);">{{ $payment->reference_number }}</code></td>
                    <td style="font-weight:700;">PHP {{ number_format($payment->amount, 2) }}</td>
                    <td>
                        <div style="font-size:0.85rem;">Sem {{ $payment->semester }}</div>
                        <div style="font-size:0.7rem; color:var(--text-muted);">{{ $payment->school_year }}</div>
                    </td>
                    <td>
                        <span class="status-badge {{ $payment->status === 'paid' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : '---' }}</td>
                    <td>
                        @if($payment->status === 'pending')
                        <form action="{{ route('admin.finance.status', $payment) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="paid">
                            <button type="submit" class="btn btn-success-modern btn-sm">
                                <i class="fas fa-check me-1"></i> Confirm Paid
                            </button>
                        </form>
                        @else
                            <span class="text-muted" style="font-size:0.8rem;"><i class="fas fa-lock me-1"></i> Locked</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding:3rem; color:var(--text-muted);">
                        No billing records found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
