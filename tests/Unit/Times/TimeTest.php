<?php

namespace Tests\Unit\Times;

use App\Calendar\Time;
use Tests\TestCase;

class TimeTest extends TestCase
{

    /**
     *@test
     */
    public function can_create_from_int_val()
    {
        $cases = collect([
            ['string' => '00:00', 'int' => 0],
            ['string' => '1:00', 'int' => 100],
            ['string' => '11:15', 'int' => 1115],
            ['string' => '14:01', 'int' => 1401],
            ['string' => '23:59', 'int' => 2359],
        ]);

        $cases->each(
            fn ($case) => $this->assertEquals($case['string'], Time::fromInt($case['int'])->timeString)
        );
    }

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

    /**
     *@test
     */
    public function converts_time_to_int_value()
    {
        $cases = collect([
            ['string' => '00:00', 'int' => 0],
            ['string' => '1:00', 'int' => 100],
            ['string' => '01:00', 'int' => 100],
            ['string' => '11:15', 'int' => 1115],
            ['string' => '14:01', 'int' => 1401],
            ['string' => '23:59', 'int' => 2359],
        ]);

        $cases->each(
            fn ($case) => $this->assertEquals($case['int'], (new Time($case['string']))->intVal())
        );
    }

    /**
     *@test
     */
    public function check_if_given_time_is_before()
    {
        $time = new Time("11:15");

        $pass_cases = collect([
            new Time("11:16"),
            new Time("12:15"),
            new Time("12:00"),
            new Time("22:00"),
        ]);

        $fail_cases = collect([
            new Time("10:59"),
            new Time("11:14"),
            new Time("00:00"),
            new Time("9:00"),
        ]);

        $fail_cases->each(fn (Time $subject) => $this->assertFalse($time->isBefore($subject), "Failed asserting {$time->timeString} is NOT before {$subject->timeString}"));
        $pass_cases->each(fn (Time $subject) => $this->assertTrue($time->isBefore($subject), "Failed to assert {$time->timeString } is before {$subject->timeString}"));
    }

    /**
     *@test
     */
    public function check_if_before_or_same()
    {
        $time = new Time("11:23");

        $this->assertTrue($time->isSameOrBefore(new Time("11:23")));
        $this->assertTrue($time->isSameOrBefore(new Time("11:24")));
        $this->assertFalse($time->isSameOrBefore(new Time("11:20")));
    }

    /**
     *@test
     */
    public function check_if_time_comes_after()
    {
        $time = new Time("11:15");

        $fail_cases = collect([
            new Time("11:16"),
            new Time("12:15"),
            new Time("12:00"),
            new Time("22:00"),
        ]);

        $pass_cases = collect([
            new Time("10:59"),
            new Time("11:14"),
            new Time("00:00"),
            new Time("9:00"),
        ]);

        $fail_cases->each(fn (Time $subject) => $this->assertFalse($time->isAfter($subject), "Failed asserting {$time->timeString} is NOT after {$subject->timeString}"));
        $pass_cases->each(fn (Time $subject) => $this->assertTrue($time->isAfter($subject), "Failed to assert {$time->timeString } is after {$subject->timeString}"));
    }

    /**
     *@test
     */
    public function check_if_same_or_after()
    {
        $time = new Time("11:23");

        $this->assertTrue($time->isSameOrAfter(new Time("11:23")));
        $this->assertTrue($time->isSameOrAfter(new Time("11:22")));
        $this->assertFalse($time->isSameOrAfter(new Time("12:20")));
    }
}
