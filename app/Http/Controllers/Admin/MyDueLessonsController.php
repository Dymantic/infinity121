<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyDueLessonsController extends Controller
{
    public function index()
    {
        return Lesson::dueByTeacher(request()->user()->profile)
            ->with('course', 'course.area')
            ->latest()
            ->limit(50)
            ->get()->map->presentForTeacher();
    }
}
