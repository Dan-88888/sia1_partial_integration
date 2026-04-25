<x-mail::message>
# Enrollment Confirmed

Dear {{ $enrollment->student->user->name }},

This email confirms that you have successfully enrolled in the following subject:

**Subject:** {{ $enrollment->section->subject->subject_name }} ({{ $enrollment->section->subject->subject_code }})
**Section:** {{ $enrollment->section->section_name }}
**Schedule:** {{ $enrollment->section->day }} | {{ date('h:i A', strtotime($enrollment->section->start_time)) }} - {{ date('h:i A', strtotime($enrollment->section->end_time)) }}
**Room:** {{ $enrollment->section->room->name ?? 'TBA' }}

<x-mail::button :url="route('enrollments.index')">
View My Schedule
</x-mail::button>

You can now download your Official Certificate of Registration (COR) from the student dashboard.

Thanks,<br>
The Registrar Office<br>
{{ config('app.name') }}
</x-mail::message>
