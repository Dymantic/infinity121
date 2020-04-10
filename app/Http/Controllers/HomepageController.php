<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Teaching\Subject;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function show()
    {
        $subjects = Subject::public()->latest()->take(4)->get()->map->forCurrentLang();
        $selling_points = ['location', 'personalised', 'quality', 'one-stop', 'business', 'specialised'];
        $teachers = Profile::inOrder()->teachers()->active()->latest()->take(3)->get()->map->forCurrentLang();
        $testimonials = trans('home.testimonials.all');

        return view('front.home.page', [
            'light' => true,
            'subjects' => $subjects,
            'selling_points' => $selling_points,
            'teachers' => $teachers,
            'testimonials' => $testimonials,
        ]);
    }
}
