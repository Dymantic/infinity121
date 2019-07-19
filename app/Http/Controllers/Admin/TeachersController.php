<?php

namespace App\Http\Controllers\Admin;

use App\Notifications\TeacherRegistered;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeachersController extends Controller
{
    public function store()
    {
        request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed']
        ]);

        return tap(User::addTeacher(request()->all()), function($teacher) {
            $teacher->notify(new TeacherRegistered($teacher, request('password')));
        });
    }
}
