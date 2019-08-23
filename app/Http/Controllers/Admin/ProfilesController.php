<?php

namespace App\Http\Controllers\Admin;

use App\Profile;
use App\Rules\ReasonableStartingYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{

    public function show()
    {
        return request()->user()->fresh()->profile;
    }

    public function update(Profile $profile)
    {
        request()->validate([
            'name'            => ['required'],
            'teaching_since'  => ['integer', new ReasonableStartingYear()],
            'chinese_ability' => ['integer', 'min:1', 'max:4']
        ]);

        $profile->updateWithTranslations(request()->only([
            'name',
            'bio',
            'nationality',
            'teaching_since',
            'chinese_ability',
            'qualifications',
        ]));
    }
}
