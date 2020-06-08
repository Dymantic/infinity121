<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseTeacherController extends Controller
{
    public function store(Course $course)
    {
        request()->validate(['profile_id' => ['required', 'exists:profiles,id']]);

        $course->assignTeacher(request('profile_id'));
    }

    public function destroy(Course $course)
    {
        $course->clearTeacher();
    }
}
