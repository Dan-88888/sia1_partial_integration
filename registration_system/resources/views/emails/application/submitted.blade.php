<x-mail::message>
# Application Received

Dear {{ $application->name }},

Thank you for your interest in Partido State University. Your application for **{{ ucfirst($application->type) }}** has been successfully submitted and is now under review.

**Your Tracking Number:**
<x-mail::panel>
# {{ $application->tracking_number }}
</x-mail::panel>

You can check your application status at any time by clicking the button below.

<x-mail::button :url="route('applications.status', ['tracking_number' => $application->tracking_number])">
Check Application Status
</x-mail::button>

Please keep your tracking number safe as it will be required for all future inquiries.

Thanks,<br>
The Admissions Team<br>
{{ config('app.name') }}
</x-mail::message>
