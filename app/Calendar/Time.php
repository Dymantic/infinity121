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
            throw new InvalidArgumentException($time_string . ' is not a valid time string [hh:mm]');
        }

        $this->timeString = $time_string;
    }

    public function hours(): int
    {
        return intval(Str::before($this->timeString, ':'));
    }

    public function minutes(): int
    {
        return intval(Str::after($this->timeString, ':'));
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
