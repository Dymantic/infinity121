<?php

namespace App\Http\Controllers\Admin;

use App\CustomerAffairs\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseLocationController extends Controller
{
    public function store(Course $course)
    {
        request()->validate([
            'area_id' => ['exists:areas,id'],
            'address' => ['required'],
        ]);

        $course->setLocationData(request()->only([
            'area_id', 'address', 'map_link', 'location_notes'
        ]));
    }
}
