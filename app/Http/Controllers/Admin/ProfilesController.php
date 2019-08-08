<?php

namespace App\Http\Controllers\Admin;

use App\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfilesController extends Controller
{
    public function update(Profile $profile)
    {
        $profile->updateWithTranslations(request()->all());
    }
}
