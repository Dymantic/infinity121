<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use App\Http\Requests\LogLessonRequest;
use App\Rules\TimeOfDay;
use Illuminate\Http\Request;

class LessonsLogController extends Controller
{
    public function store(Lesson $lesson, LogLessonRequest $request)
    {
        $lesson->log(request()->user()->profile,
            $request->lessonLog()
        );
    }
}
