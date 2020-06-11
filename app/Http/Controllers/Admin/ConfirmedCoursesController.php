<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Course;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmCourseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ConfirmedCoursesController extends Controller
{
    public function store(ConfirmCourseRequest $request)
    {
        $request->course()->confirm($request->startFromDate());
        $request->course()->setNextLesson();
    }
}
