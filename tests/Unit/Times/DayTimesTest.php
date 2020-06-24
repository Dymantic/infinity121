<?php


namespace Tests\Unit\Times;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use Carbon\Carbon;
use Tests\TestCase;

class DayTimesTest extends TestCase
{
    /**
     *@test
     */
    public function make_my_day()
    {
        $day = new Day(1, [
            new TimePeriod("10:00", "12:00"),
        ]);

        $this->assertInstanceOf(Day::class, $day);
        $this->assertEquals(1, $day->weekDayInt());
        $this->assertEquals('Monday', $day->weekDayString());
        $this->assertCount(1, $day->periods);
    }

    /**
     *@test
     */
    public function add_period_to_empty_day()
    {
        $day = new Day(1);
        $period = new TimePeriod("10:00", "12:00");

        $day = $day->addPeriod($period);

        $this->assertCount(1, $day->periods);
        $this->assertTrue($day->periods->contains($period));
    }


    /**
     *@test
     */
    public function add_period_to_day_with_other_periods()
    {
        $periodA = new TimePeriod("10:00", "12:00");
        $periodB = new TimePeriod("14:00", "17:00");
        $periodC = new TimePeriod("18:00", "20:00");

        $day = new Day(1, [$periodA, $periodB]);

        $day = $day->addPeriod($periodC);

        $this->assertCount(3, $day->periods);
        $this->assertTrue($day->periods->contains($periodA));
        $this->assertTrue($day->periods->contains($periodB));
        $this->assertTrue($day->periods->contains($periodC));
    }

    /**
     *@test
     */
    public function add_overlapping_period()
    {
        $original = new TimePeriod("10:00", "12:00");
        $overlapping = new TimePeriod("11:00", "14:00");
        $expected = new TimePeriod("10:00", "14:00");

        $day = new Day(1, [$original]);

        $day = $day->addPeriod($overlapping);

        $this->assertCount(1, $day->periods);
        $this->assertTrue($day->periods->contains($expected));
    }

    /**
     *@test
     */
    public function add_with_three_way_overlap()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("11:00", "15:00");
        $expected = new TimePeriod("10:00", "17:00");

        $day = new Day(1, [$originalA, $originalB]);

        $day = $day->addPeriod($overlapping);

        $this->assertCount(1, $day->periods);
        $this->assertTrue($day->periods->contains($expected));
    }

    /**
     *@test
     */
    public function add_with_overlap_and_untouched()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $originalC = new TimePeriod("18:00", "20:00");
        $overlapping = new TimePeriod("17:30", "19:00");
        $expectedA = new TimePeriod("10:00", "12:00");
        $expectedB = new TimePeriod("14:00", "17:00");
        $expectedC = new TimePeriod("17:30", "20:00");

        $day = new Day(1, [$originalA, $originalB, $originalC]);

        $day = $day->addPeriod($overlapping);

        $this->assertCount(3, $day->periods);
        $this->assertTrue($day->periods->contains($expectedA));
        $this->assertTrue($day->periods->contains($expectedB));
        $this->assertTrue($day->periods->contains($expectedC));
    }

    /**
     *@test
     */
    public function add_with_pure_overlap()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("14:00", "17:00");
        $expectedA = new TimePeriod("10:00", "12:00");
        $expectedB = new TimePeriod("14:00", "17:00");

        $day = new Day(1, [$originalA, $originalB]);

        $day = $day->addPeriod($overlapping);

        $this->assertCount(2, $day->periods);
        $this->assertTrue($day->periods->contains($expectedA));
        $this->assertTrue($day->periods->contains($expectedB));
    }

    /**
     *@test
     */
    public function clear_with_no_periods()
    {
        $day = new Day(1);

        $day = $day->clearPeriod(new TimePeriod("10:00", "11:00"));

        $this->assertCount(0, $day->periods);
    }

    /**
     *@test
     */
    public function clear_with_one_side_overlap()
    {
        $original = new TimePeriod("10:00", "12:00");
        $overlapping = new TimePeriod("09:00", "11:00");
        $expected = new TimePeriod("11:00", "12:00");

        $day = new Day(1, [$original]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(1, $day->periods);
        $this->assertTrue($day->periods->contains($expected));

    }

    /**
     *@test
     */
    public function clear_with_other_side_overlap()
    {
        $original = new TimePeriod("10:00", "12:00");
        $overlapping = new TimePeriod("11:30", "13:00");
        $expected = new TimePeriod("10:00", "11:30");

        $day = new Day(1, [$original]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(1, $day->periods);
        $this->assertTrue($day->periods->contains($expected));
    }

    /**
     *@test
     */
    public function clear_with_multi_overlap()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("11:30", "15:00");
        $expectedA = new TimePeriod("10:00", "11:30");
        $expectedB = new TimePeriod("15:00", "17:00");

        $day = new Day(1, [$originalA, $originalB]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(2, $day->periods);
        $this->assertTrue($day->periods->contains($expectedA));
        $this->assertTrue($day->periods->contains($expectedB));
    }

    /**
     *@test
     */
    public function clear_10_to_12_time_block()
    {
        $day = new Day(Carbon::WEDNESDAY, [
            new TimePeriod("10:00", "12:00")
        ]);
        $day = $day->clearPeriod(new TimePeriod("10:00", "11:00"));
//        dd($day);

        $this->assertTrue($day->periods->contains(new TimePeriod("11:00", "12:00")));
    }

    /**
     *@test
     */
    public function clear_with_multi_overlap_and_normal()
    {
        $originalA = new TimePeriod("8:30", "9:00");
        $originalB = new TimePeriod("10:00", "12:00");
        $originalC = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("11:30", "15:00");
        $expectedA = new TimePeriod("8:30", "9:00");
        $expectedB = new TimePeriod("10:00", "11:30");
        $expectedC = new TimePeriod("15:00", "17:00");

        $day = new Day(1, [$originalA, $originalB, $originalC]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(3, $day->periods);
        $this->assertTrue($day->periods->contains($expectedA));
        $this->assertTrue($day->periods->contains($expectedB));
        $this->assertTrue($day->periods->contains($expectedC));
    }

    /**
     *@test
     */
    public function clear_with_complete_over_wrap()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("10:00", "17:00");

        $day = new Day(1, [$originalA, $originalB]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(0, $day->periods);
    }

    /**
     *@test
     */
    public function clear_with_empty_period()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("10:00", "10:00");

        $day = new Day(1, [$originalA, $originalB]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(2, $day->periods);
        $this->assertTrue($day->periods->contains($originalA));
        $this->assertTrue($day->periods->contains($originalB));
    }

    /**
     *@test
     */
    public function clear_with_no_overlap()
    {
        $originalA = new TimePeriod("10:00", "12:00");
        $originalB = new TimePeriod("14:00", "17:00");
        $overlapping = new TimePeriod("18:00", "22:00");

        $day = new Day(1, [$originalA, $originalB]);

        $day = $day->clearPeriod($overlapping);

        $this->assertCount(2, $day->periods);
        $this->assertTrue($day->periods->contains($originalA));
        $this->assertTrue($day->periods->contains($originalB));
    }
}
