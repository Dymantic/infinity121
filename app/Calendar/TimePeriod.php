<?php


namespace App\Calendar;


class TimePeriod
{
    public Time $start;
    public Time $end;

    public function __construct(string $start_time, string $end_time)
    {
        $this->start = new Time($start_time);
        $this->end = new Time($end_time);

        if ($this->end->isBefore($this->start)) {
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

    public function isSameAs(TimePeriod $period): bool
    {
        return $this->startAsInt() === $period->startAsInt() && $this->endAsInt() === $period->endAsInt();
    }

    public function isEmpty(): bool
    {
        return $this->startAsInt() === $this->endAsInt();
    }

    public function contains(Time $time): bool
    {
        return $this->start->isSameOrBefore($time) && $this->end->isSameOrAfter($time);
    }

    public function overlaps(TimePeriod $period): bool
    {
        return !($this->start->isAfter($period->end) || $this->end->isBefore($period->start));
    }

    public function merge(TimePeriod $period): TimePeriod
    {
        $start = $this->start->isSameOrBefore($period->start) ? $this->start : $period->start;
        $end = $this->end->isSameOrAfter($period->end) ? $this->end : $period->end;

        return new TimePeriod($start->timeString, $end->timeString);
    }

    public function exclude(TimePeriod $period): Exclusion
    {
        if ($period->start->isSameOrBefore($this->start) && $period->end->isSameOrAfter($this->end)) {
            return new Exclusion(null, null);
        }

        if ($period->start->isAfter($this->start) && $period->end->isBefore($this->end)) {
            return new Exclusion(
                new TimePeriod($this->start->timeString, $period->start->timeString),
                new TimePeriod($period->end->timeString, $this->end->timeString),
            );
        }

        if ($period->isEmpty() || !$this->overlaps($period)) {
            return new Exclusion($this, null);
        }


        if ($this->start->isSameOrBefore($period->start)) {
            return new Exclusion(
                new TimePeriod($this->start->timeString, $period->start->timeString), null
            );
        }

        return new Exclusion(null, new TimePeriod($period->end->timeString, $this->end->timeString));

    }
}
