<?php

namespace App\Http\Controllers\Admin;

use App\Affiliates\Affiliate;
use App\Http\Controllers\Controller;
use App\Rules\RequiresContent;
use Illuminate\Http\Request;

class AffiliatesController extends Controller
{

    public function index()
    {
        return Affiliate::all()->map->toArray();
    }

    public function show(Affiliate $affiliate)
    {
        return $affiliate;
    }

    public function store()
    {
        request()->validate([
            'name' => [new RequiresContent()],
            'link' => ['url'],
        ]);

        return Affiliate::createNew(request()->all());
    }

    public function update(Affiliate $affiliate)
    {
        request()->validate([
            'name' => [new RequiresContent()],
            'link' => ['url']
        ]);
        $affiliate->updateWithTranslations(request()->all());

        return $affiliate->fresh();
    }

    public function delete(Affiliate $affiliate)
    {
        $affiliate->delete();
    }
}
