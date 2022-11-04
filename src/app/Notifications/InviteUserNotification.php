<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class InviteUserNotification extends Notification
{
    use Queueable;

    protected $inviter;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $inviter)
    {
        $this->inviter = $inviter;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {


        $token = Password::broker('invites')->createToken($notifiable);


        return (new MailMessage)
            ->subject('Invitation to join ' . config('app.name'))
            ->from(env('MAIL_FROM_EMAIL'), $this->inviter->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->inviter->name . ' invited you to SCAN. In order to accept the invitation, please click the button below.')
            ->line('You will be asked to set a password.')
            ->action('Finalize my account creation !', url('/reset-password/' . $token . '?email=' . $notifiable->email))
            ->line('You can find a complete documentation of the application here: ' . url('https://user-doc.netw4ppl.tech'))
            ->line('Thank you for using our application!');

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
