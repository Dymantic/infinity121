<?php

namespace Tests\Unit\Times;

use App\Calendar\Time;
use Tests\TestCase;

class TimeTest extends TestCase
{
    /**
     *@test
     */
    public function check_if_time_string_is_valid()
    {
        $invalid_cases = collect([
            ['value' => 'not-a-time', 'message' => 'not a time string'],
            ['value' => 1, 'message' => 'not a string'],
            ['value' => "10:3", 'message' => 'needs two digit minutes'],
            ['value' => "100:30", 'message' => 'needs two digit hours'],
            ['value' => "10:300", 'message' => 'needs two digit minutes'],
            ['value' => "25:30", 'message' => 'hours out of range'],
            ['value' => "20:60", 'message' => 'minutes out of range'],
        ]);

        $invalid_cases->each(
            fn($case) => $this->assertFalse(Time::isValidString($case['value']), $case['message'])
        );

        $valid_cases = collect([
            ['value' => '00:00'],
            ['value' => '23:59'],
            ['value' => "13:05"],
            ['value' => "06:30"],
            ['value' => "6:30"],
        ]);

        $valid_cases->each(
            fn($case) => $this->assertTrue(Time::isValidString($case['value']))
        );
    }
}
