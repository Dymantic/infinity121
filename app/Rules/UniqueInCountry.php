<?php

namespace App\Rules;

use App\Locations\Country;
use Illuminate\Contracts\Validation\Rule;

class UniqueInCountry implements Rule
{

    private Country $country;
    private ?int $ignore;

    public function __construct(Country $country, int $ignore = null)
    {
        $this->country = $country;
        $this->ignore = $ignore;
    }

    public function passes($attribute, $value)
    {
        return $this->country->regions()
                             ->whereKeyNot($this->ignore)
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
