<?php

namespace App\Rules;

use App\Locations\Country;
use Illuminate\Contracts\Validation\Rule;

class UniqueInCountry implements Rule
{

    private Country $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    public function passes($attribute, $value)
    {
        return $this->country->regions()
                             ->where('name', $value)->count() === 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':input already exists in ' . $this->country->name;
    }
}
