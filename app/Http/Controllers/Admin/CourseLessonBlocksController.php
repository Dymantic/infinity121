<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Course;
use App\Http\Controllers\Controller;
use App\Rules\TimeOfDay;
use Illuminate\Http\Request;

class CourseLessonBlocksController extends Controller
{
    public function store(Course $course)
    {
        request()->validate([
            'blocks' => ['required', 'array'],
            'blocks.*' => ['array'],
            'blocks.*.day_of_week' => ['required', 'integer', 'min:0', 'max:6'],
            'blocks.*.starts' => ['required', new TimeOfDay],
            'blocks.*.ends' => ['required', new TimeOfDay, 'after:blocks.*.starts'],
        ]);

        $course->setLessonBlocks(request('blocks'));
    }
}
