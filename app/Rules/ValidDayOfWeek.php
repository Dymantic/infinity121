<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidDayOfWeek implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(is_null($value)) {
            return false;
        }
        if(!is_int($value)) {
            return false;
        }

        if($value < 0) {
            return false;
        }

        if($value > 6) {
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
        return 'Day of the week should be represented as an integer between 0 (Sunday) and 6 (Saturday)';
    }
}
