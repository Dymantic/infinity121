<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Locations\Country;
use App\Locations\Region;
use App\Rules\UniqueInCountry;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    public function store(Country $country)
    {
        request()->validate([
            'name' => ['required', new UniqueInCountry($country)]
        ]);

        $country->addRegion(request('name'));
    }

    public function update(Region $region)
    {
        request()->validate([
            'name' => ['required', new UniqueInCountry($region->country, $region->id)]
        ]);

        $region->update(request()->only('name'));
    }

    public function delete(Region $region)
    {
        $region->fullDelete();
    }
}
