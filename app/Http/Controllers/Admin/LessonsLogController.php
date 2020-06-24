<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use App\Rules\TimeOfDay;
use Illuminate\Http\Request;

class LessonsLogController extends Controller
{
    public function store(Lesson $lesson)
    {
        request()->validate([
            'completed_on' => ['required', 'date'],
            'actual_start' => ['required', new TimeOfDay()],
            'actual_end' => ['required', new TimeOfDay(), 'after:actual_start'],
        ]);

        $lesson->log(request()->user()->profile,
            request()->only(['teacher_log', 'student_report', 'material_taught', 'completed_on', 'actual_start', 'actual_end'])
        );
    }
}
