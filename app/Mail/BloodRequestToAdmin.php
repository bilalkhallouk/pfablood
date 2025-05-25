<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BloodRequestToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $bloodRequest;
    public $prescriptionPath;

    /**
     * Create a new message instance.
     */
    public function __construct($bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
        $this->prescriptionPath = $bloodRequest->prescription_file
            ? storage_path('app/public/' . $bloodRequest->prescription_file)
            : null;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Blood Request To Admin',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bloodrequest',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }

    public function build()
    {
        $email = $this->markdown('emails.bloodrequest')
            ->subject('Nouvelle demande de sang');
        if ($this->prescriptionPath && file_exists($this->prescriptionPath)) {
            $email->attach($this->prescriptionPath, [
                'as' => 'ordonnance.' . pathinfo($this->prescriptionPath, PATHINFO_EXTENSION),
                'mime' => mime_content_type($this->prescriptionPath),
            ]);
        }
        return $email;
    }
}
