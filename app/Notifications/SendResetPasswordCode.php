<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendResetPasswordCode extends Notification
{
    use Queueable;

    public $user;


    public $code;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user ,$code)
    {
        $this->user = $user;

        $this->code = $code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Verification needed')
                    ->line('Hi,')
                    ->line('Please use code below to reset your password')
                    ->action($this->code, url('#'))
                    ->line('Thank you for choosing nollyflix!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
