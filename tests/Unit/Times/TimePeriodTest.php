<?php


namespace Tests\Unit\Times;


use App\Calendar\Time;
use App\Calendar\TimePeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimePeriodTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function check_if_period_contains_time()
    {
        $period = new TimePeriod("10:00", "12:00");

        $this->assertTrue($period->contains(new Time("10:00")));
        $this->assertTrue($period->contains(new Time("11:00")));
        $this->assertTrue($period->contains(new Time("10:01")));
        $this->assertTrue($period->contains(new Time("11:59")));
        $this->assertTrue($period->contains(new Time("12:00")));
        $this->assertFalse($period->contains(new Time("9:59")));
        $this->assertFalse($period->contains(new Time("12:10")));
    }

    /**
     *@test
     */
    public function check_if_period_overlaps_with_another()
    {
        $period = new TimePeriod("10:00", "12:00");

        $this->assertTrue(
            $period->overlaps(new TimePeriod("9:00", "11:00"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("10:40", "12:25"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("9:00", "10:00"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("12:00", "15:00"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("9:00", "15:00"))
        );

        $this->assertFalse(
            $period->overlaps(new TimePeriod("9:00", "9:55"))
        );
        $this->assertFalse(
            $period->overlaps(new TimePeriod("12:01", "18:55"))
        );
    }
}
