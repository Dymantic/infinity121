<?php

namespace App\Teaching;

use App\Calendar\Time;
use Illuminate\Database\Eloquent\Model;

class AvailablePeriod extends Model
{
    protected $fillable = ['day_of_week', 'starts', 'ends'];

    public function timePeriod()
    {
        return [
            "starts" => Time::fromInt($this->starts)->timeString,
            "ends" => Time::fromInt($this->ends)->timeString,
        ];
    }
}
