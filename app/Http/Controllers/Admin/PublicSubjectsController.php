<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Teaching\Subject;
use Illuminate\Http\Request;

class PublicSubjectsController extends Controller
{
    public function store()
    {
        $subject = Subject::findOrFail(request('subject_id'));

        $subject->publish();

        return $subject->fresh();
    }

    public function destroy(Subject $subject)
    {
        $subject->retract();
    }
}
