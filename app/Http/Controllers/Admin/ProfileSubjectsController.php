<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class ProfileSubjectsController extends Controller
{
    public function update(Profile $profile)
    {
        request()->validate([
            'subject_ids' => ['array'],
            'subject_ids.*' => ['exists:subjects,id']
        ]);
        $profile->assignSubjects(request('subject_ids'));
    }
}
