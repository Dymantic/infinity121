<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ActiveCoursesController extends Controller
{
    public function index()
    {
        return Course::active()->latest('starts_from')->get()->map->toArray();
    }
}
