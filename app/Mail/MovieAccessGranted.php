<?php

namespace App\Mail;

use App\MovieAccessGrant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MovieAccessGranted extends Mailable
{
    use Queueable, SerializesModels;

    public $grant;

    public function __construct(MovieAccessGrant $grant)
    {
        $this->grant = $grant;
    }

    public function build()
    {
        return $this->subject('Your movie is ready: '.$this->grant->video->title)
            ->view('emails.movie-access-granted');
    }
}
