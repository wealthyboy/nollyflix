<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CastsEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $casts;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($casts)
    {
        $this->casts = $casts;
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
                        ->greeting('Congratulations '. $this->casts->casts['first_name']) 
                        ->line('We have added you to nollyflix. ') 
                        ->line('Your username is: '.$this->casts->casts['email'])
                        ->line('Your password is: '.$this->casts->casts['password'])
                        ->line('Your profile page is: https:// nollyflix.tv/profile/'.str_slug($this->casts['first_name'].' '.$this->casts['last_name'])) 
                        ->line('Your can  login and check how your movies are doing through your dashboard') 
                        ->subject("Your profile have been created")
                        ->action('Click here to visit your dashboard', url('/login'))
                        ->line('Please note only you can update your profile information');

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
