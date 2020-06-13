<?php


namespace App\Calendar;


class TimePeriod
{
    private Time $start;
    private Time $end;

    public function __construct(string $start_time, string $end_time)
    {
        $this->start = new Time($start_time);
        $this->end = new Time($end_time);

        if($this->end->isSameOrBefore($this->start)) {
            throw new \InvalidArgumentException("Period start must be before the end");
        }
    }

    public function startAsInt(): int
    {
        return $this->start->intVal();
    }

    public function endAsInt(): int
    {
        return $this->end->intVal();
    }

    public function contains(Time $time): bool
    {
        return $this->start->isSameOrBefore($time) && $this->end->isSameOrAfter($time);
    }

    public function overlaps(TimePeriod $period): bool
    {
        return !($this->start->isAfter($period->end) || $this->end->isBefore($period->start));
    }
}
