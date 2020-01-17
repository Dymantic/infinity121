<?php

namespace App\Http\Controllers;

use App\Profile;
use App\Teaching\Subject;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    public function show()
    {
        $subjects = Subject::latest()->take(4)->get()->map->forCurrentLang();
        $selling_points = ['location', 'personalised', 'quality', 'one-stop', 'business', 'specialised'];
        $teachers = Profile::teachers()->latest()->take(3)->get()->map->forCurrentLang();

        return view('front.home.page', [
            'light' => true,
            'subjects' => $subjects,
            'selling_points' => $selling_points,
            'teachers' => $teachers
        ]);
    }
}
