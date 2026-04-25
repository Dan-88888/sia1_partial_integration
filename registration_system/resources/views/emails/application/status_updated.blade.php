<x-mail::message>
# Application Status Update

Dear {{ $application->name }},

We would like to inform you that your application with tracking number **{{ $application->tracking_number }}** has been **{{ strtoupper($application->status) }}**.

@if($application->status === 'Approved')
Congratulations! You can now access the University Portal using the following temporary credentials:

**Email:** {{ $application->university_email }}  
*(Example: jcruz001.pbox@parsu.edu.ph)*

**Your Personalized Password:** `{{ $password }}`  
*(Your password is your **First Name** + **Year Level** + **Year of Semester**)*  
*Example: juan126*

<x-mail::button :url="route('login')">
Login to Portal
</x-mail::button>
@else
We regret to inform you that we are unable to approve your application at this time. If you have any questions, please contact the Admissions Office.
@endif

Thanks,<br>
The Admissions Team<br>
{{ config('app.name') }}
</x-mail::message>
