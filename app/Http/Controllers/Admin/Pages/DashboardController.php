<?php

namespace App\Http\Controllers\Admin\Pages;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke()
    {
        return view('admin.api-base', ['user' => request()->user()]);
    }
}
