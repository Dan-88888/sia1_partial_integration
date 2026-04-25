<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
        .status-box { padding: 15px; text-align: center; font-weight: bold; font-size: 18px; border-radius: 5px; margin: 20px 0; }
        .approved { background-color: #dcfce7; color: #166534; }
        .rejected { background-color: #fee2e2; color: #991b1b; }
        .waitlisted { background-color: #fef9c3; color: #854d0e; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #000035; color: #ffffff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #000035;">Status Update</h1>
        </div>
        <p>Dear {{ $application->firstname }},</p>
        <p>Your admission application status has been updated. Please see the details below:</p>
        
        <div class="status-box {{ strtolower($application->status) }}">
            Current Status: {{ $application->status }}
        </div>
        
        @if($application->status === 'Approved')
            <p>Congratulations! Your application has been approved. Please visit the campus registrar with your original documents to complete your enrollment.</p>
        @elseif($application->status === 'Rejected')
            <p>We regret to inform you that your application was not successful at this time. We wish you the best in your future academic endeavors.</p>
        @elseif($application->status === 'Waitlisted')
            <p>Your application has been placed on the waitlist. We will notify you if a slot becomes available in your chosen program.</p>
        @endif
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('student.status', $application->id) }}" class="btn" style="color: white;">View Full Details</a>
        </div>
        
        <p>Best regards,<br>University Admission Team</p>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
