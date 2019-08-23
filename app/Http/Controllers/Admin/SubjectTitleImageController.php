<?php

namespace App\Http\Controllers\Admin;

use App\Teaching\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectTitleImageController extends Controller
{
    public function store(Subject $subject)
    {
        request()->validate([
            'image' => ['required', 'image']
        ]);

        $image = $subject->setTitleImage(request('image'));

        return ['image_src' => $image->fresh()->getUrl('thumb')];
    }
}
