<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COR - {{ $student->student_number }}</title>
    <style>
        body { font-family: 'Arial', sans-serif; padding: 40px; color: #333; line-height: 1.4; }
        .header { text-align: center; border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .school-name { font-size: 24px; font-weight: bold; text-transform: uppercase; color: #003366; }
        .cor-title { font-size: 18px; font-weight: bold; margin-top: 10px; }
        .student-info { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .info-item { margin-bottom: 8px; font-size: 14px; }
        .info-label { font-weight: bold; color: #666; width: 120px; display: inline-block; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 13px; }
        th { background: #f2f2f2; border: 1px solid #ddd; padding: 12px; text-align: left; }
        td { border: 1px solid #ddd; padding: 10px; }
        .assessment { margin-top: 20px; border: 1px solid #000; padding: 20px; width: 300px; margin-left: auto; }
        .fee-item { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .total { border-top: 2px solid #000; margin-top: 10px; padding-top: 10px; font-weight: bold; font-size: 16px; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; font-size: 12px; }
        .signature-line { border-top: 1px solid #000; width: 200px; text-align: center; margin-top: 40px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #003366; color: white; border: none; cursor: pointer; border-radius: 5px;">Print COR</button>
        <a href="{{ route('enrollments.cor.download') }}" style="margin-left: 10px; padding: 10px 20px; background: #5d4037; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">Download PDF</a>
        <a href="{{ route('enrollments.index') }}" style="margin-left: 10px; color: #666; text-decoration: none;">Back to Portal</a>
    </div>

    <div class="header">
        <div class="school-name">{{ $app_settings['school_name'] ?? 'Partido State University' }}</div>
        <div>Goa, Camarines Sur, Philippines</div>
        <div class="cor-title">CERTIFICATE OF REGISTRATION</div>
        <div>{{ ($app_settings['active_semester'] ?? '1') == '1' ? '1st' : ($app_settings['active_semester'] == '2' ? '2nd' : $app_settings['active_semester']) }} Semester, SY {{ $app_settings['active_school_year'] ?? '2024-2025' }}</div>
    </div>

    <div class="student-info">
        <div>
            <div class="info-item"><span class="info-label">Student No:</span> {{ $student->student_number }}</div>
            <div class="info-item"><span class="info-label">Name:</span> {{ strtoupper($student->user->name) }}</div>
            <div class="info-item"><span class="info-label">Course:</span> {{ $student->course ?? 'BSIT' }}</div>
        </div>
        <div style="text-align: right;">
            <div class="info-item"><span class="info-label">Date:</span> {{ now()->format('M d, Y') }}</div>
            <div class="info-item"><span class="info-label">Year Level:</span> {{ $student->year_level }}</div>
            <div class="info-item"><span class="info-label">Status:</span> REGULAR</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>SECTION</th>
                <th>SUBJECT CODE</th>
                <th>DESCRIPTION</th>
                <th>SCHEDULE</th>
                <th>ROOM</th>
                <th>UNITS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
            @php $section = $enrollment->section; @endphp
            <tr>
                <td>{{ $section->section_name }}</td>
                <td>{{ $section->subject->subject_code }}</td>
                <td>{{ $section->subject->subject_name }}</td>
                <td>{{ $section->day }} {{ date('H:i', strtotime($section->start_time)) }}-{{ date('H:i', strtotime($section->end_time)) }}</td>
                <td>{{ $section->room->name ?? 'TBA' }}</td>
                <td style="text-align: center;">{{ $section->subject->units }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="text-align: right; font-weight: bold;">TOTAL UNITS:</td>
                <td style="text-align: center; font-weight: bold;">{{ $student->totalUnits() }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="assessment">
        <div style="font-weight: bold; margin-bottom: 15px; border-bottom: 1px solid #ddd; padding-bottom: 5px;">ASSESSMENT OF FEES</div>
        <div class="fee-item">
            <span>Tuition Fee ({{ $student->totalUnits() }} units)</span>
            <span>PHP {{ number_format($student->totalUnits() * 500, 2) }}</span>
        </div>
        <div class="fee-item">
            <span>Miscellaneous Fee</span>
            <span>PHP 1,500.00</span>
        </div>
        <div class="fee-item">
            <span>Lab Fees</span>
            <span>PHP 0.00</span>
        </div>
        <div class="total">
            <span>TOTAL AMOUNT</span>
            <span>PHP {{ number_format($student->totalTuition(), 2) }}</span>
        </div>
    </div>

    <div class="footer">
        <div>
            <div class="signature-line">Registrar Representative</div>
            <div style="margin-top: 5px;">Date Signed: _______________</div>
        </div>
        <div>
            <div class="signature-line">Student's Signature</div>
            <div style="margin-top: 5px;">Date Signed: _______________</div>
        </div>
    </div>

    <div style="margin-top: 40px; font-size: 10px; color: #666; text-align: center;">
        This is an official document of {{ $app_settings['school_name'] ?? 'Partido State University' }}. Any alteration void this document.
    </div>
</body>
</html>
