<?php

namespace App\Http\Controllers\Admin;

use App\Affiliates\Affiliate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AffiliateImagesController extends Controller
{
    public function store(Affiliate $affiliate)
    {
        request()->validate([
            'image' => ['required', 'image'],
        ]);

        $image = $affiliate->addImage(request('image'));

        return ['image_src' => $image->getUrl('thumb')];
    }
}
