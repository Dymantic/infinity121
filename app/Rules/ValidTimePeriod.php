<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTimePeriod implements Rule
{

    public function passes($attribute, $value)
    {
        if(!is_array($value) || count($value) !== 2) {
            return false;
        }

        $starts = $value[0];
        $ends = $value[1];

        if(!is_int($starts) || !is_int($ends)) {
            return false;
        }

        if(!$this->isValidTimeInt($starts) || !$this->isValidTimeInt($ends)) {
            return false;
        }

        if(intval($starts) >= intval($ends)) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'A time period must be represented as a tuple of time strings in the format of "1400"';
    }

    private function isValidTimeInt($time_int)
    {
        if(!is_int($time_int) || $time_int < 100) {
            return false;
        }

        $minutes = $time_int % 100;
        $hours = floor($time_int / 100);

        if($minutes > 59 || $minutes < 0) {
            return false;
        }

        if($hours > 23 || $hours < 0) {
            return false;
        }

        return true;
    }
}
