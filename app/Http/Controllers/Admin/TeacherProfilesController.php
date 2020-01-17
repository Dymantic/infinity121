<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class TeacherProfilesController extends Controller
{
    public function index()
    {
        return Profile::teachers()->with('subjects')->get()->map->toArray();
    }
}
