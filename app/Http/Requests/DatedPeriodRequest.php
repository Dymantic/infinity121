<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class DatedPeriodRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after:from']
        ];
    }

    public function starts()
    {
        return Carbon::parse($this->from);
    }

    public function ends()
    {
        return Carbon::parse($this->to);
    }
}
