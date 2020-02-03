<?php


namespace App\Notifications;


class Message
{
    public $name;
    public $email;
    public $message;

    public function __construct($data)
    {
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->message = $data['message'];
    }
}
