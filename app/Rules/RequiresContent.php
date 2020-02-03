<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequiresContent implements Rule
{

    public function __construct()
    {
        //
    }


    public function passes($attribute, $value)
    {
        if(!is_array($value)) {
            return false;
        }

        return !! collect($value)->reject(function($translation) {
//            dump($translation)
            return !$translation;
        })->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
