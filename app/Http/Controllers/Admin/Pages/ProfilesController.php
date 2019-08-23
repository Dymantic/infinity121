<?php

namespace App\Http\Controllers\Admin\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{

    public function show()
    {
        return view('admin.profiles.show', ['profile' => request()->user()->profile]);
    }
}
