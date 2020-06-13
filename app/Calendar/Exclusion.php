<?php


namespace App\Calendar;


class Exclusion
{

    public ?TimePeriod $before;
    public ?TimePeriod $after;

    public function __construct(?TimePeriod $before, ?TimePeriod $after)
    {

        $this->before = $before;
        $this->after = $after;
    }
}
