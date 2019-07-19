<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\AdminRegistered;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Notifications\Notification;

class AdminUsersController extends Controller
{
    public function store()
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'is_teacher' => ['boolean']
        ]);

        return tap(User::addAdmin(request()->all()), function($admin) {
            $admin->notify(new AdminRegistered($admin, request('password')));
        });
    }
}
