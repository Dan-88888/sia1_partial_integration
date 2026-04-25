<?php

namespace App\Mail;

use App\Models\StudentApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public StudentApplication $application)
    {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admission Status Update: ' . $this->application->status,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.status-updated',
        );
    }
}
