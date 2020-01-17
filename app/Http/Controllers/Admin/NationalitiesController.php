<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Nationalities;
use Illuminate\Http\Request;

class NationalitiesController extends Controller
{
    public function index()
    {
        return Nationalities::forLang("en");
    }
}
