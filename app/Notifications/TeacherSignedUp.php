<?php

namespace App\Notifications;

use App\Teachers\TeacherInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherSignedUp extends Notification
{
    use Queueable;

    public $inquiry;

    public function __construct(TeacherInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
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
                    ->subject("Teacher sign up from {$this->inquiry->name}")
                    ->markdown('mail.teacher-sign-up', ['inquiry' => $this->inquiry->toArray()]);
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
