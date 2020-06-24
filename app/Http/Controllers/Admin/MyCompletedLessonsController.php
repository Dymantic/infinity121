<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyCompletedLessonsController extends Controller
{
    public function index()
    {
        return Lesson::completedByTeacher(request()->user()->profile)
            ->latest('completed_on')
            ->limit(30)
            ->get()->map->presentForTeacher();
    }
}
