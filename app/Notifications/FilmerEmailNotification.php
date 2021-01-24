<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FilmerEmailNotification extends Notification
{
    use Queueable;

    public $filmers;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->filmers = $data;
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
        ->greeting('Congratulations '. $this->filmers['first_name'] . '!') 
        ->line('Your  profile have been approved for Nollyflix. You are now eligible to earn 10% of every film sold or rented from  your actors page. ') 
        ->line('Your are therefore encouraged to promote your page by telling your fans to watch your movies through your profile page link') 
        ->line('Your username is: '.$this->filmers['email'])
        ->line('Your password is: '.$this->filmers['password'])
        ->line('Your profile page is: https://nollyflix.tv/'.str_slug($this->filmers['username'])) 
        ->line('You can also make the link available to them on your social media platforms. ') 
        ->line('You will be able to Access your sales by logging into your dashboard. You can also edit your Profile and change your password through your dashboard.') 
        ->subject("Your profile has been created")
        ->action('Click here to visit your dashboard', url('/profile'));
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
