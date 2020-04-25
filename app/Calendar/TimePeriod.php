<?php


namespace App\Calendar;


class TimePeriod
{
    private $start_time;
    private $end_time;

    public function __construct(string $start_time, string $end_time)
    {
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }

    public function starts()
    {
        return intval($this->start_time);
    }

    public function ends()
    {
        return intval($this->end_time);
    }
}
