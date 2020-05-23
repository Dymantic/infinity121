<?php

namespace App\Rules;

use App\Locations\Region;
use Illuminate\Contracts\Validation\Rule;

class UniqueInRegion implements Rule
{

    private Region $region;
    private $ignore;

    public function __construct(Region $region, $ignore = null)
    {
        $this->region = $region;
        $this->ignore = $ignore;
    }


    public function passes($attribute, $value)
    {
        return $this->region->areas()->whereKeyNot($this->ignore)->where('name', $value)->count() === 0;
    }

    public function message()
    {
        return ':input already exists in ' . $this->region->name;
    }
}
