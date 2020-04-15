<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Teaching\Subject;
use Illuminate\Http\Request;

class SubjectsOrderController extends Controller
{
    public function store()
    {
        request()->validate([
            'subject_ids' => ['required', 'array'],
            'subject_ids.*' => ['exists:subjects,id']
        ]);
        Subject::setOrder(request('subject_ids'));
    }
}
