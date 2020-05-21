<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Locations\Area;
use App\Locations\Region;
use App\Rules\UniqueInRegion;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    public function store(Region $region)
    {
        request()->validate([
            'name' => ['required', new UniqueInRegion($region)]
        ]);

        $region->addArea(request('name'));
    }

    public function update(Area $area)
    {
        request()->validate([
            'name' => ['required', new UniqueInRegion($area->region)]
        ]);

        $area->update(request()->only('name'));
    }

    public function delete(Area $area)
    {
        $area->delete();
    }
}
