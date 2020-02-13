<?php

namespace App\Http\Controllers;

use App\Profile;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    public function index()
    {
        return view('front.teachers.index', [
            'teachers' => Profile::teachers()->active()->get()->map->forCurrentLang()
        ]);
    }

    public function show($slug)
    {
        return view('front.teachers.show', [
            'teacher' => Profile::active()->whereSlug($slug)->firstOrFail()->forCurrentLang()
        ]);
    }
}
