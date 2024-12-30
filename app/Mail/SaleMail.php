<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SaleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $contact;
    public function __construct($contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        $contact = $this->contact;
        return $this->subject('Nuevo mensaje de contacto')
            ->replyTo($contact->email, $contact->name) // Configura el correo del usuario en "Reply-To"
            ->view('emails.contact', compact("contact"));
    }
}
