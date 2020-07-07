<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Lesson;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CancelledLessonsController extends Controller
{
    public function store()
    {
        request()->validate([
            'reason' => ['required'],
        ]);

        $lesson = Lesson::findOrFail(request('lesson_id'));

        $lesson->cancel(request()->user()->profile, request('reason'));
    }
}
