<?php

namespace App\Http\Controllers\Admin;

use App\Teaching\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectsController extends Controller
{

    public function index()
    {
        return Subject::all();
    }

    public function store()
    {
        request()->validate(['title' => ['required']]);

        return Subject::createNew(request('title'));
    }

    public function update(Subject $subject)
    {
        $subject->updateWithTranslations(request()->only(['title', 'description', 'writeup']));
    }

    public function delete(Subject $subject)
    {
        $subject->safeDelete();
    }
}
