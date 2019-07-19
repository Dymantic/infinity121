<?php

namespace App\Http\Controllers\Admin;

use App\Rules\CurrentPassword;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersPasswordController extends Controller
{
    public function update()
    {
        request()->validate([
            'old_password' => ['required', new CurrentPassword(request()->user())],
            'new_password' => ['required', 'min:6', 'confirmed']
        ]);
        request()->user()->updatePassword(request('new_password'));
    }
}
