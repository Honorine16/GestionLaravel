<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private $name,
        private $email,
        private $code,
    )
    {
        //
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Code de confirmation Application de Gestion de Fichier',
            from: new Address('accounts@unetah.net', 'Message de AppGestionFichier')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.otpcode',
            with: [
                'name' => $this->name,
                'email' => $this->email,
                'code' => $this->code,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
