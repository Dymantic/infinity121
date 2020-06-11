<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Course;
use App\CustomerAffairs\Customer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerCoursesController extends Controller
{

    public function index(Customer $customer)
    {
        return $customer->courses->map->toArray();
    }

    public function show(Course $course)
    {
        return $course->toArray();
    }

    public function store(Customer $customer)
    {
        request()->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'total_lessons' => ['required', 'integer'],
            'students' => ['required', 'array'],
            'students.*' => ['array'],
            'students.*.name' => ['required'],
            'students.*.age' => ['required'],
        ]);

        $course = $customer->addCourse(request()->only([
            'subject_id',
            'students',
            'total_lessons',
        ]));

        return $course;
    }

    public function update(Course $course)
    {
        request()->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
            'total_lessons' => ['required', 'integer'],
            'students' => ['required', 'array'],
            'students.*' => ['array'],
            'students.*.name' => ['required'],
            'students.*.age' => ['required'],
        ]);

        $course->update(request()->only([
            'subject_id',
            'students',
            'total_lessons',
            'starts_from'
        ]));
    }
}
