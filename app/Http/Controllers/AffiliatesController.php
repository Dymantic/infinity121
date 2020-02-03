<?php

namespace App\Http\Controllers;

use App\Affiliates\Affiliate;
use Illuminate\Http\Request;

class AffiliatesController extends Controller
{
    public function index()
    {
        return view('front.affiliates.page', ['affiliates' => Affiliate::public()->get()->map->forCurrentLang()]);
    }
}
