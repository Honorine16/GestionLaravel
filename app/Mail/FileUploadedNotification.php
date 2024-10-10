<?php

namespace App\Mail;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address as MailablesAddress;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FileUploadedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $file;
    /**
     * Create a new message instance.
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Message d\'envoi des fichiers dans d\'un groupe dans l\'Application de Gestion de Fichier',
            from: new MailablesAddress('accounts@unetah.net', 'Message du groupe dans l\'application de Gestion de Fichiers')
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.file_uploaded',
            with: [

            ]
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
}
