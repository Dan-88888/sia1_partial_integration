<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Inter', Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 30px; font-size: 12px; color: #777; text-align: center; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #000035; color: #ffffff; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: #000035;">Application Received</h1>
        </div>
        <p>Dear {{ $application->firstname }},</p>
        <p>Thank you for applying for admission. We have successfully received your application and it is now being reviewed by our administrative team.</p>
        
        <p><strong>Application Details:</strong></p>
        <ul>
            <li><strong>Application ID:</strong> #{{ str_pad($application->id, 6, '0', STR_PAD_LEFT) }}</li>
            <li><strong>Campus:</strong> {{ $application->campus }}</li>
            <li><strong>Course:</strong> {{ $application->course }}</li>
            <li><strong>Status:</strong> Pending</li>
        </ul>
        
        <p>You can track the progress of your application at any time by visiting our tracking portal.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('student.track') }}" class="btn" style="color: white;">Track My Application</a>
        </div>
        
        <p>Best regards,<br>University Admission Team</p>
        
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
