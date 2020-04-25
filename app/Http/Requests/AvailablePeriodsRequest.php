<?php

namespace App\Http\Requests;

use App\Calendar\TimePeriod;
use App\Rules\ValidDayOfWeek;
use App\Rules\ValidTimePeriod;
use Illuminate\Foundation\Http\FormRequest;

class AvailablePeriodsRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            'day_of_week' => new ValidDayOfWeek,
            'periods' => ['required', 'array'],
            'periods.*' => new ValidTimePeriod,
        ];
    }

    public function teacher()
    {
        return $this->user()->profile;
    }

    public function day()
    {
        return $this->day_of_week;
    }

    public function timePeriods()
    {
        return collect($this->periods)
            ->map(fn ($times) => new TimePeriod($times[0], $times[1]))
            ->all();
    }
}
