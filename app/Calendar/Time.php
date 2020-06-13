<?php


namespace App\Calendar;


use Illuminate\Support\Str;
use InvalidArgumentException;

class Time
{

    public $timeString;

    public function __construct($time_string)
    {
        if(!self::isValidString($time_string)) {
            throw new InvalidArgumentException(
                $time_string . ' is not a valid time string [hh:mm]'
            );
        }

        $this->timeString = $time_string;
    }

    public static function fromInt(int $timeInt): self
    {
        if($timeInt < 100 && $timeInt > 59) {
            throw new InvalidArgumentException(
                sprintf("%s cannot represent a valid time", $timeInt)
            );
        }

        if($timeInt < 10) {
            return new Time(sprintf("00:0%s", $timeInt));
        }

        if($timeInt < 60) {
            return new Time(sprintf("00:%s", $timeInt));
        }

        $hours = floor($timeInt / 100);
        $min = $timeInt % 100;

        if($min < 10) {
            $min = sprintf("0%s", $min);
        }

        return new Time(sprintf("%s:%s", $hours, $min));

    }

    public function hours(): int
    {
        return intval(Str::before($this->timeString, ':'));
    }

    public function minutes(): int
    {
        return intval(Str::after($this->timeString, ':'));
    }

    public function intVal(): int
    {
        return intval(str_replace(":", "", $this->timeString));
    }

    public function isBefore(Time $time): bool
    {
        return $this->intVal() < $time->intVal();
    }

    public function isSameOrBefore(Time $time): bool
    {
        return $this->intVal() <= $time->intVal();
    }

    public function isAfter(Time $time): bool
    {
        return $this->intVal() > $time->intVal();
    }

    public function isSameOrAfter(Time $time): bool
    {
        return $this->intVal() >= $time->intVal();
    }

    public static function isValidString($subject)
    {
        $matches = [];
        $found = preg_match('/^([0-2]?[0-9]):([0-5][0-9])$/', $subject, $matches);

        if(!$found) {
            return false;
        }

        [, $hours, $mins] = $matches;

        return self::validHours(intval($hours)) && self::validMinutes($mins);

    }

    private static function validHours(int $hours): bool
    {
        return $hours >= 0 && $hours <= 23;
    }

    private static function validMinutes(int $mins): bool
    {
        return $mins >= 0 && $mins <= 59;
    }
}
