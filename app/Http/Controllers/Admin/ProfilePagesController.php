<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilePagesController extends Controller
{
    public function show()
    {
        return view('admin.profiles.show', ['profile' => request()->user()->profile]);
    }
}
