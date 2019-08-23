<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileImageController extends Controller
{
    public function update()
    {
        request()->validate(['image' => ['image']]);

        $teacher = request()->user()->load('profile');

        $image = $teacher->profile->setProfileImage(request('image'));

        return ['image_src' => $image->getUrl('thumb')];
    }
}
