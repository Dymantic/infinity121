<?php

namespace App\Rules;

use App\Calendar\Time;
use Illuminate\Contracts\Validation\Rule;

class TimeOfDay implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
        return Time::isValidString($value);
    }


    public function message()
    {
        return ':attribute should be in 24h time format [hh:mm]';
    }
}
