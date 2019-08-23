<?php

namespace App\Http\Controllers\Admin\Pages;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{

    public function index()
    {
        return view('admin.users.index');
    }

    public function show(User $user)
    {
        return view('admin.users.show', ['user' => $user]);
    }
}
