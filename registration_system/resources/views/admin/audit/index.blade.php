@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="w-100">
        <h1 class="page-title"><i class="fas fa-fingerprint me-2" style="color:var(--navy);"></i> System Audit Logs</h1>
        <p class="page-subtitle">Track every critical administrative and academic action</p>
    </div>
</div>

<div class="glass-card" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern w-100">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model Info</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="font-size:0.85rem; font-weight:600; color:var(--text-primary);">
                            {{ $log->created_at->format('M d, Y') }}
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">
                            {{ $log->created_at->format('h:i:s A') }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-sm" style="background:#f1f5f9; color:var(--navy); font-weight:bold; width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.7rem; border:1px solid #e2e8f0;">
                                {{ substr($log->user->name ?? 'SYS', 0, 1) }}
                            </div>
                            <div>
                                <div style="font-size:0.85rem; font-weight:600;">{{ $log->user->name ?? 'System' }}</div>
                                <div style="font-size:0.7rem; color:var(--text-muted);">{{ ucfirst($log->user->role ?? 'automation') }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="status-badge info">{{ $log->action }}</span>
                    </td>
                    <td>
                        @if($log->model_type)
                            <div style="font-size:0.75rem;">
                                <i class="fas fa-link me-1" style="color:var(--navy);"></i>
                                {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                            </div>
                        @else
                            <span class="text-muted" style="font-size:0.75rem;">N/A</span>
                        @endif
                    </td>
                    <td>
                        <code style="font-size:0.75rem; color:var(--navy-light);">{{ $log->ip_address }}</code>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding:3rem; color:var(--text-muted);">
                        No audit logs recorded yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
