<?php

namespace App\CustomerAffairs;

use App\Calendar\Time;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class LessonBlock extends Model
{
    protected $fillable = ['day_of_week', 'starts', 'ends'];

    protected $casts = ['day_of_week' => 'integer'];

    public function asNextDate($after = null)
    {
        $time = new Time($this->starts);

        $date = Carbon::parse($after)
                      ->startOfDay()
                      ->setHour($time->hours())
                      ->setMinutes($time->minutes());

        if (
            ($this->day_of_week === Carbon::today()->weekDay()) &&
            ($this->day_of_week === $date->weekDay()) &&
            (Carbon::parse($after)->lessThan($date)))
        {
            return $date;
        }

        return $date
            ->next($this->dayName($this->day_of_week))
            ->startOfDay()
            ->setHour($time->hours())
            ->setMinutes($time->minutes());
    }

    public function toTimeTuple()
    {
        return [
            'starts' => $this->starts,
            'ends' => $this->ends,
        ];
    }

    private function dayName(int $day): string
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return $days[$day];
    }
}
