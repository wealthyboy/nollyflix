<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class AccountDeletionReceived extends Mailable
{
    public $reference;

    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    public function build()
    {
        return $this->subject('NollyFlix: verified account deletion request')
            ->view('emails.account-deletion-received');
    }
}
