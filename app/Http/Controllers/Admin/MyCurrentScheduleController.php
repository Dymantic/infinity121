<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyCurrentScheduleController extends Controller
{
    public function show()
    {
        return request()->user()->profile->currentSchedule();
    }
}
