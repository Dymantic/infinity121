<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AboutPageController extends Controller
{
    public function show()
    {
        $team = [
            [
                'name' => trans('leadership.names.keith-gillibrand'),
                'role' => trans('leadership.roles.founder-ceo'),
                'avatar' => '/images/profiles/keith_gillibrand.jpg',
                'page-link' => 'founder',
            ],
            [
                'name' => trans('leadership.names.kay-gillibrand'),
                'role' => trans('leadership.roles.liaison-japan'),
                'avatar' => '/images/profiles/kay_gillibrand.jpg',
            ],
//            [
//                'name' => trans('leadership.names.keith-gillibrand'),
//                'role' => trans('leadership.roles.liaison-business'),
//                'avatar' => '/images/profiles/default.jpg',
//            ],
//            [
//                'name' => trans('leadership.names.keith-gillibrand'),
//                'role' => trans('leadership.roles.liaison-toefl'),
//                'avatar' => '/images/profiles/goofy.jpg',
//            ],
        ];
        return view('front.about.page', ['team' => $team]);
    }
}
