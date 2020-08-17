<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public $settings;

    public $currency;

    public $user;



    public function __construct($user,$order,$settings,$symbol)
    {
        $this->order = $order;
        
        $this->settings = $settings;

        $this->currency = $symbol;

        $this->user = $user;
    }

    
    public function build()
    {   
        return $this->subject('Order Confirmation')->view('emails.receipt.index');
    }
}
