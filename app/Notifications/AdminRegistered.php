<?php

namespace App\Notifications;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class AdminRegistered extends Notification
{
    use Queueable;

    public $admin;
    public $password;


    public function __construct(User $admin, $password)
    {
        $this->admin = $admin;
        $this->password = $password;
    }


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
                    ->subject('Welcome to Infinity121')
                    ->markdown('mail.users.admin-registered', [
                        'name' => $this->admin->name,
                        'email' => $this->admin->email,
                        'password' => $this->password,
                        'url' => url("/admin"),
                    ]);
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
