@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-wallet me-2" style="color:var(--gold);"></i> My Billing & Payments</h1>
        <p class="page-subtitle">Track your tuition fees and payment history</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4" data-aos="fade-right">
        <div class="glass-card h-100">
            <div class="card-header-modern">
                <i class="fas fa-info-circle"></i>
                <h4>Payment Instructions</h4>
            </div>
            <p style="font-size:0.9rem; color:var(--text-secondary); margin-bottom:1.5rem;">
                Tuition fees are calculated based on registered units (PHP 500/unit) plus a fixed miscellaneous fee of PHP 1,500.
            </p>
            <div class="d-flex flex-column gap-3">
                <div class="p-3" style="background:rgba(255,255,255,0.03); border-radius:10px; border-left:4px solid var(--gold);">
                    <div style="font-weight:700; color:var(--gold); font-size:0.85rem;">BY CASHIER</div>
                    <div style="font-size:0.8rem;">Present your student ID and reference number at the University Cashier window.</div>
                </div>
                <div class="p-3" style="background:rgba(255,255,255,0.03); border-radius:10px; border-left:4px solid var(--gold);">
                    <div style="font-weight:700; color:var(--gold); font-size:0.85rem;">BANK TRANSFER</div>
                    <div style="font-size:0.8rem;">Landbank Acct: 1234-5678-90<br>Account Name: Partido State University</div>
                </div>
            </div>
            <div class="mt-4 p-3 bg-danger-soft" style="border-radius:10px; border:1px dashed var(--danger);">
                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                <small class="text-danger">Delayed payments may affect your final grade release or subsequent semester enrollment.</small>
            </div>
        </div>
    </div>

    <div class="col-lg-8" data-aos="fade-up">
        <div class="glass-card">
            <div class="card-header-modern mb-4">
                <i class="fas fa-receipt"></i>
                <h4>Billing Statements</h4>
            </div>

            <div class="table-responsive">
                <table class="table-modern w-100">
                    <thead>
                        <tr>
                            <th>Reference #</th>
                            <th>Term</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                        <tr>
                            <td><code style="color:var(--gold); font-weight:bold;">{{ $payment->reference_number }}</code></td>
                            <td>
                                <div style="font-size:0.85rem; font-weight:600;">Sem {{ $payment->semester }}</div>
                                <div style="font-size:0.7rem; color:var(--text-muted);">{{ $payment->school_year }}</div>
                            </td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">PHP {{ number_format($payment->amount, 2) }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">Tuition + Misc</div>
                            </td>
                            <td>
                                <span class="status-badge {{ $payment->status === 'paid' ? 'success' : 'warning' }}">
                                    <i class="fas {{ $payment->status === 'paid' ? 'fa-check-circle' : 'fa-clock' }} me-1"></i>
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                @if($payment->payment_date)
                                    <div style="font-size:0.8rem;">Paid on:</div>
                                    <div style="font-size:0.85rem; font-weight:600;">{{ $payment->payment_date->format('M d, Y') }}</div>
                                @else
                                    <span class="text-muted" style="font-size:0.85rem;">Pending Confirmation</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center" style="padding:4rem; color:var(--text-muted);">
                                <i class="fas fa-coins" style="font-size:2.5rem; display:block; margin-bottom:1rem; opacity:0.1;"></i>
                                No billing statements found. Start enrolling to generate your assessment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
