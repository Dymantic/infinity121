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
                'avatar' => '/images/profiles/default.jpg',
            ],
            [
                'name' => 'Sandy Hsu',
                'role' => trans('leadership.roles.senior-partner'),
                'avatar' => '/images/profiles/default.jpg',
            ],
            [
                'name' => 'Jen Jenkins',
                'role' => trans('leadership.roles.senior-partner'),
                'avatar' => '/images/profiles/default.jpg',
            ],
            [
                'name' => 'Percy Chan',
                'role' => trans('leadership.roles.hr-manager'),
                'avatar' => '/images/profiles/default.jpg',
            ],
        ];
        return view('front.about.page', ['team' => $team]);
    }
}
