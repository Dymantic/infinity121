<?php

namespace App\Teaching;

use App\Calendar\Time;
use App\Calendar\TimePeriod;
use Illuminate\Database\Eloquent\Model;

class AvailablePeriod extends Model
{
    protected $fillable = ['day_of_week', 'starts', 'ends'];

    protected $casts = [
        'day_of_week' => 'integer',
        'starts'      => 'integer',
        'ends'        => 'integer',
    ];

    public function timeStringTuple()
    {
        return [
            "starts" => Time::fromInt($this->starts)->timeString,
            "ends"   => Time::fromInt($this->ends)->timeString,
        ];
    }

    public function timePeriod(): TimePeriod
    {
        return new TimePeriod(
            Time::fromInt($this->starts)->timeString, Time::fromInt($this->ends)->timeString,
        );
    }
}
