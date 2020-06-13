<?php

namespace App\Rules;

use App\Calendar\Time;
use Illuminate\Contracts\Validation\Rule;

class ValidTimePeriod implements Rule
{

    public function passes($attribute, $value)
    {
        if(!is_array($value) || count($value) !== 2) {
            return false;
        }

        try {
            $start = new Time($value[0]);
            $end = new Time($value[1]);
        } catch(\Exception $e) {
            return false;
        }

        return $start->isBefore($end);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Times must be presented as hh:mm (eg 12:00), and the start should come before the end';
    }
}
