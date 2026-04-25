@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">{{ $subject->subject_name }}</h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th>Subject Code:</th>
                            <td>{{ $subject->subject_code }}</td>
                        </tr>
                        <tr>
                            <th>Course:</th>
                            <td>{{ $subject->course->course_name ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Day:</th>
                            <td>{{ $subject->day }}</td>
                        </tr>
                        <tr>
                            <th>Time:</th>
                            <td>{{ date('h:i A', strtotime($subject->start_time)) }} - {{ date('h:i A', strtotime($subject->end_time)) }}</td>
                        </tr>
                        <tr>
                            <th>Room:</th>
                            <td>{{ $subject->room }}</td>
                        </tr>
                        <tr>
                            <th>Instructor:</th>
                            <td>{{ $subject->instructor ?? 'TBA' }}</td>
                        </tr>
                        <tr>
                            <th>Units:</th>
                            <td>{{ $subject->units }}</td>
                        </tr>
                        <tr>
                            <th>Capacity:</th>
                            <td>{{ $currentEnrollment }} / {{ $subject->capacity }} ({{ $availableSlots }} slots available)</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ route('subjects.index') }}" class="btn btn-secondary">Back to Subjects</a>
            </div>
        </div>
    </div>
</div>
@endsection