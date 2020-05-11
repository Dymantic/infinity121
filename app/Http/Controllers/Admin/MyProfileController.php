<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyProfileController extends Controller
{
    public function show()
    {
        return request()->user()->fresh()->profile->toArray();
    }
}
