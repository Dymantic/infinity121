<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Profile;
use Illuminate\Http\Request;

class PublishedProfilesController extends Controller
{
    public function store()
    {
        $profile = Profile::findOrFail(request('profile_id'));

        $profile->publish();
    }

    public function destroy(Profile $profile)
    {
        $profile->retract();
    }
}
