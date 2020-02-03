<?php

namespace App\Http\Controllers\Admin;

use App\Affiliates\Affiliate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublishedAffiliatesController extends Controller
{
    public function store()
    {
        $affiliate = Affiliate::findOrFail(request('affiliate_id'));

        $affiliate->publish();
    }

    public function destroy(Affiliate $affiliate)
    {
        $affiliate->retract();
    }
}
