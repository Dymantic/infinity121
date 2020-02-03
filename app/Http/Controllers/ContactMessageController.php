<?php

namespace App\Http\Controllers;

use App\Notifications\ContactMessage;
use App\Notifications\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class ContactMessageController extends Controller
{

    public function create()
    {
        $labels = [
            'name' => trans('forms.name'),
            'email' => trans('forms.email'),
            'message' => trans('forms.message'),
            'send' => trans('forms.send'),
        ];
        return view('front.contact.page', ['labels' => $labels]);
    }

    public function store()
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $message = new Message(request()->only('name', 'email', 'message'));

        Notification::send(User::admins()->get(), new ContactMessage($message));
    }
}
