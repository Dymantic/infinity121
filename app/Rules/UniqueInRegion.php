<?php

namespace App\Rules;

use App\Locations\Region;
use Illuminate\Contracts\Validation\Rule;

class UniqueInRegion implements Rule
{

    private Region $region;

    public function __construct(Region $region)
    {
        $this->region = $region;
    }


    public function passes($attribute, $value)
    {
        return $this->region->areas()->where('name', $value)->count() === 0;
    }

    public function message()
    {
        return ':input already exists in ' . $this->region->name;
    }
}
