@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="section-header" data-aos="fade-down">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div>
            <h1 class="page-title"><i class="fas fa-door-open me-2" style="color:var(--navy);"></i> Room Management</h1>
            <p class="page-subtitle">Configure and manage campus facilities and room capacities</p>
        </div>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-navy">
            <i class="fas fa-plus me-2"></i> Add New Room
        </a>
    </div>
</div>

<!-- Main Table Card -->
<div class="glass-card" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Room Name</th>
                    <th>Building</th>
                    <th>Floor Level</th>
                    <th>Seating Capacity</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rooms as $room)
                <tr>
                    <td class="fw-bold">{{ $room->name }}</td>
                    <td>{{ $room->building ?? '-' }}</td>
                    <td>{{ $room->floor ?? '-' }}</td>
                    <td>
                        <span class="badge bg-light text-success border">{{ $room->capacity ?? '-' }} Pax</span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-navy btn-sm" title="Edit Room">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" onsubmit="return confirm('Delete this room?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-modern btn-sm" title="Delete Room">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $rooms->links() }}
    </div>
</div>
@endsection
