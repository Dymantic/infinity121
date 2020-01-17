<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{

    public function index()
    {
        return User::with('profile')->get();
    }

    public function show()
    {
        return request()->user()->toArray();
    }

    public function update()
    {
        $user = request()->user();

        request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)]
        ]);

        $user->update(request()->only('name', 'email'));

        return $user->fresh();
    }
}
