<?php


namespace App\Calendar;


use Illuminate\Support\Collection;

class Day
{

    public int $week_day;
    public Collection $periods;

    public function __construct(int $week_day, array $periods = [])
    {
        $this->week_day = $week_day;
        $this->periods = collect($periods);
    }

    public function weekDayInt(): int
    {
        return $this->week_day;
    }

    public function weekDayString(): string
    {
        return DateFormatter::WEEKDAYS[$this->week_day];
    }

    public function addPeriod(TimePeriod $period): Day
    {
        $hasOverlap = $this->periods->contains(fn (TimePeriod $p) => $p->overlaps($period));

        if(!$hasOverlap) {
            return new Day($this->week_day, $this->periods->push($period)->toArray());
        }

        $firstOverlapIndex = $this->periods->search(fn (TimePeriod $p) => $p->overlaps($period));
        $overlapper = $this->periods->pull($firstOverlapIndex);

        return (new Day($this->week_day, $this->periods->toArray()))
            ->addPeriod($overlapper->merge($period));
    }

    public function clearPeriod(TimePeriod $period): Day
    {
        if($this->periods->count() === 0) {
            return $this;
        }

        $hasOverlap = $this->periods->contains(fn (TimePeriod $p) => $p->overlaps($period));

        if(!$hasOverlap) {
            return $this;
        }

        $excluded = $this->periods
            ->map(fn (TimePeriod $p) => $p->exclude($period))
            ->reduce(function($carry, Exclusion $exclusion) {
               if($exclusion->before) {
                   array_push($carry, $exclusion->before);
               }

                if($exclusion->after) {
                    array_push($carry, $exclusion->after);
                }

                return $carry;
            }, []);

        $day = new Day($this->week_day);

        foreach ($excluded as $excluded_period) {
            $day = $day->addPeriod($excluded_period);
        }

        return $day;
    }
}
