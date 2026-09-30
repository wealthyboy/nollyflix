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

    public $cart;




    public function __construct($user,$order,$cart,$settings,$symbol)
    {
        $this->order = $order;

         $this->cart = $cart;
        
        $this->settings = $settings;

        $this->currency = $symbol;

        $this->user = $user;
    }

    
    public function build()
    {   
        return $this->bcc('Contact@fortressfilmstudios.com')
            ->subject(strtolower((string) $this->cart->purchase_type) === 'rent' ? 'Your Nollyflix Rental Receipt' : 'Your Nollyflix Purchase Receipt')
            ->view('emails.receipt.index');
    }
}
