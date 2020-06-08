<?php


namespace App\Calendar;


class Time
{
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
