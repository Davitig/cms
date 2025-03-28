<?php

namespace App\Mail;

use App\Models\Setting\WebSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class FeedbackSubmitted extends Mailable
{
    /**
     * Create a new message instance.
     */
    public function __construct(protected array $data, protected array $files = []) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                (string) config('mail.from.address'),
                (string) config('mail.from.name')
            ),
            to: $this->getRecipient(),
            subject: 'Feedback'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'web.mail.feedback',
            with: $this->data
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        foreach ($this->files as $file) {
            if ($file instanceof UploadedFile) {
                $attachments[] = Attachment::fromData(
                    fn () => $file->get(), $file->getClientOriginalName()
                );
            }
        }

        return $attachments;
    }

    /**
     * Get a recipient.
     *
     * @return string|null
     */
    public function getRecipient(): ?string
    {
        return (new WebSetting)->findByName('email')->value;
    }
}
