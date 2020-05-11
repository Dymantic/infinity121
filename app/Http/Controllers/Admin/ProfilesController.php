<?php

namespace App\Http\Controllers\Admin;

use App\Profile;
use App\Rules\ReasonableStartingYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{

    public function show(Profile $profile)
    {
        return $profile->toArray();
    }

    public function update(Profile $profile)
    {
        if(request()->user()->id !== $profile->user->id && !request()->user()->is_admin) {
            abort(403);
        }

        request()->validate([
            'name'            => ['required'],
            'teaching_since'  => ['integer', new ReasonableStartingYear()],
            'chinese_ability' => ['integer', 'min:1', 'max:4'],
            'spoken_languages' => ['array'],
            'spoken_languages.*' => ['in:en,sp,jp,zh,fr,de']
        ]);

        $profile->updateWithTranslations(request()->only([
            'name',
            'bio',
            'nationality',
            'teaching_since',
            'chinese_ability',
            'qualifications',
            'spoken_languages',
        ]));
    }
}
