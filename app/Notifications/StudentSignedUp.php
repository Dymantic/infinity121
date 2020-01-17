<?php

namespace App\Notifications;

use App\Students\StudentInquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentSignedUp extends Notification
{
    use Queueable;
    /**
     * @var StudentInquiry
     */
    public $inquiry;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(StudentInquiry $inquiry)
    {
        $this->inquiry = $inquiry;
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
            ->subject("Student sign-up from {$this->inquiry->name}")
            ->markdown('mail.student-sign-up', ['inquiry' => $this->inquiry->toArray()]);
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
