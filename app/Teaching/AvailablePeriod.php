<?php

namespace App\Teaching;

use Illuminate\Database\Eloquent\Model;

class AvailablePeriod extends Model
{
    protected $fillable = ['day_of_week', 'starts', 'ends'];

    public function timePeriod()
    {
        return ["starts" => $this->starts, "ends" => $this->ends];
    }
}
