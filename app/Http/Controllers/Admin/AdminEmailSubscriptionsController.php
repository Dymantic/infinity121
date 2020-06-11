<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdminEmailSubscriptionsController extends Controller
{
    public function store()
    {
        request()->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::find(request('user_id'));

        $user->subscribeToAdminEmails();
    }

    public function destroy(User $user)
    {
        $user->unsubscribeFromAdminEmails();
    }
}
