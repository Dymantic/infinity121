<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Locations\Country;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CountriesController extends Controller
{

    public function index()
    {
        return Country::with('regions.areas')->get()->map->toArray();
    }

    public function store()
    {
        request()->validate([
            'name' => ['required', Rule::unique('countries', 'name')]
        ]);
        Country::create(request()->only('name'));
    }

    public function update(Country $country)
    {
        request()->validate([
            'name' => ['required', Rule::unique('countries', 'name')->ignore($country->id)]
        ]);

        $country->update(request()->only('name'));
    }

    public function delete(Country $country)
    {
        $country->fullDelete();
    }
}
