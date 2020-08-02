<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FilmerEmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        ->line('Your profile page is: https://nollyflix.tv/profile/'.str_slug($this->casts->casts['first_name'].' '.$this->casts->casts['last_name'])) 
        ->line('Your can login and check how your movies are doing through your dashboard') 
        ->subject("Your profile have been created")
        ->action('Click here to visit your dashboard', url('/profile'))
        ->line('Please note only you can update your info');
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
