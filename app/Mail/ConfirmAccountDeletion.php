<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ConfirmAccountDeletion extends Mailable
{
    public $confirmationUrl;

    public function __construct($confirmationUrl)
    {
        $this->confirmationUrl = $confirmationUrl;
    }

    public function build()
    {
        return $this->subject('Confirm your NollyFlix account deletion request')
            ->view('emails.confirm-account-deletion');
    }
}
