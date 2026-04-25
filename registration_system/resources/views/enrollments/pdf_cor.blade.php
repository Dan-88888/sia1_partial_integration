<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Certificate of Registration - {{ $student->student_number }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #5d4037; padding-bottom: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #5d4037; margin-bottom: 5px; }
        .sub-header { font-size: 14px; color: #666; }
        .title { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 20px; text-decoration: underline; }
        
        .student-info { width: 100%; margin-bottom: 20px; }
        .student-info td { padding: 5px; font-size: 13px; }
        .label { font-weight: bold; width: 120px; }
        
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th { background-color: #f5f5f5; border: 1px solid #ddd; padding: 8px; font-size: 12px; text-align: left; }
        .table td { border: 1px solid #ddd; padding: 8px; font-size: 11px; }
        
        .footer { margin-top: 50px; width: 100%; }
        .signature-box { width: 200px; border-top: 1px solid #000; text-align: center; padding-top: 5px; font-size: 12px; }
        
        .total-box { text-align: right; margin-top: 10px; font-weight: bold; font-size: 14px; }
        
        .watermark { position: absolute; top: 40%; left: 20%; font-size: 60px; color: rgba(93, 64, 55, 0.05); transform: rotate(-45deg); z-index: -1; }
    </style>
</head>
<body>
    <div class="watermark">PARTIDO STATE UNIVERSITY</div>

    <div class="header">
        <div class="logo-text">PARTIDO STATE UNIVERSITY</div>
        <div class="sub-header">Goa, Camarines Sur, Philippines</div>
        <div class="sub-header">OFFICE OF THE UNIVERSITY REGISTRAR</div>
    </div>

    <div class="title">CERTIFICATE OF REGISTRATION</div>

    <table class="student-info">
        <tr>
            <td class="label">Student No:</td>
            <td>{{ $student->student_number }}</td>
            <td class="label">Date:</td>
            <td>{{ date('M d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Name:</td>
            <td>{{ strtoupper($student->user->name) }}</td>
            <td class="label">Semester:</td>
            <td>{{ $activeSemester }}</td>
        </tr>
        <tr>
            <td class="label">Course:</td>
            <td>{{ $student->course }}</td>
            <td class="label">School Year:</td>
            <td>{{ $activeSY }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Subject Description</th>
                <th>Units</th>
                <th>Day</th>
                <th>Time</th>
                <th>Room</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
            <tr>
                <td>{{ $enrollment->section->subject->subject_code }}</td>
                <td>{{ $enrollment->section->subject->subject_name }}</td>
                <td>{{ $enrollment->section->subject->units }}</td>
                <td>{{ substr($enrollment->section->day, 0, 3) }}</td>
                <td>{{ date('h:ia', strtotime($enrollment->section->start_time)) }}-{{ date('h:ia', strtotime($enrollment->section->end_time)) }}</td>
                <td>{{ $enrollment->section->room->name ?? 'TBA' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Units: {{ $enrollments->sum(fn($e) => $e->section->subject->units) }}
    </div>

    <div style="margin-top:20px; font-size: 12px; font-weight:bold;">FEES SUMMARY</div>
    <table style="width: 250px; font-size: 11px;">
        <tr>
            <td>Tuition Fee ({{ $enrollments->sum(fn($e) => $e->section->subject->units) }} units x 500)</td>
            <td style="text-align:right;">P {{ number_format($enrollments->sum(fn($e) => $e->section->subject->units) * 500, 2) }}</td>
        </tr>
        <tr>
            <td>Miscellaneous Fees</td>
            <td style="text-align:right;">P 1,500.00</td>
        </tr>
        <tr style="border-top:1px solid #000; font-weight:bold;">
            <td>TOTAL AMOUNT</td>
            <td style="text-align:right;">P {{ number_format($student->totalTuition(), 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        <table width="100%">
            <tr>
                <td>
                    <div class="signature-box">Registrar's Signature</div>
                </td>
                <td style="text-align:right;">
                    <div class="signature-box" style="margin-left:auto;">Student's Signature</div>
                </td>
            </tr>
        </table>
        <p style="font-size:10px; color:#999; text-align:center; margin-top:30px;">
            This is an officially generated document from the ParSU University Registration System.
        </p>
    </div>
</body>
</html>
