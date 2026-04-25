<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Class Roster - {{ $section->section_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #1e3a8a;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1e3a8a;
            font-size: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        .data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Partido State University</h1>
        <div>Official Class Roster</div>
    </div>

    <table class="info-table">
        <tr>
            <td width="15%"><strong>Section:</strong></td>
            <td width="35%">{{ $section->name ?? $section->section_name }}</td>
            <td width="15%"><strong>Teacher:</strong></td>
            <td width="35%">{{ $section->teacher->user->name ?? 'TBA' }}</td>
        </tr>
        <tr>
            <td><strong>Subject:</strong></td>
            <td>{{ $section->subject->subject_code }} - {{ $section->subject->subject_name }}</td>
            <td><strong>Schedule:</strong></td>
            <td>{{ $section->day }} | {{ \Carbon\Carbon::parse($section->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($section->end_time)->format('h:i A') }}</td>
        </tr>
        <tr>
            <td><strong>Room:</strong></td>
            <td>{{ $section->room->name ?? 'TBA' }}</td>
            <td><strong>Total Students:</strong></td>
            <td>{{ $enrollments->count() }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="30%">Student Number</th>
                <th width="60%">Student Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $index => $enrollment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $enrollment->student->student_number }}</td>
                <td>{{ $enrollment->student->user->name }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on {{ now()->format('F d, Y h:i A') }} &bull; System ID: {{ $section->id }}
    </div>
</body>
</html>
