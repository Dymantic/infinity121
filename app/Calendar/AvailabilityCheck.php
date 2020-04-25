<?php


namespace App\Calendar;


use App\Profile;

class AvailabilityCheck
{

    private int $day_of_week;

    public function __construct(int $day_of_week)
    {
        $this->day_of_week = $day_of_week;
    }

    public function for(TimePeriod $period)
    {
        return Profile::teachers()
            ->whereHas('availablePeriods', fn($query) => $query->where([
                ['day_of_week', $this->day_of_week],
                ['starts', '<=', $period->starts()],
                ['ends', '>=', $period->ends()]
            ]))->get();
    }

    private function checkAvailable($teacher, $period)
    {

    }
}
