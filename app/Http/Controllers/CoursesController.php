<?php

namespace App\Http\Controllers;

use App\Teaching\Subject;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index()
    {
        return view('front.courses.index', ['courses' => Subject::public()->get()->map->forCurrentLang()]);
    }

    public function show($slug)
    {
        $course = Subject::public()->where('slug', $slug)->firstOrFail();

        return view('front.courses.show', ['course' => $course->forCurrentLang()]);
    }
}
