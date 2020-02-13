<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function show()
    {
        $team = [
            [
                'name' => 'Keith Gillibrand',
                'role' => trans('leadership.roles.founder-ceo'),
                'avatar' => '/images/profiles/hero.jpg',
                'page-link' => 'founder',
            ],
            [
                'name' => 'Sandy Hsu',
                'role' => trans('leadership.roles.liaison-japan'),
                'avatar' => '/images/profiles/girl.jpg',
            ],
            [
                'name' => 'Percy Chan',
                'role' => trans('leadership.roles.liaison-business'),
                'avatar' => '/images/profiles/default.jpg',
            ],
            [
                'name' => 'Jen Jenkins',
                'role' => trans('leadership.roles.liaison-toefl'),
                'avatar' => '/images/profiles/goofy.jpg',
            ],
        ];
        return view('front.about.page', ['team' => $team]);
    }
}
