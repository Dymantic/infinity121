<?php


namespace App\Calendar;


use Illuminate\Support\Carbon;

class DateFormatter
{
    const STANDARD = 'Y-m-d';
    const PRETTY = 'jS M, Y';

    const WEEKDAYS = ['Sunday', "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", 'Saturday'];

    public static function standard($date): string
    {
        if(!$date) {
            return '';
        }

        return $date->format(DateFormatter::STANDARD);
    }

    public static function pretty($date): string
    {
        if(!$date) {
            return '';
        }

        return $date->format(DateFormatter::PRETTY);
    }
}
