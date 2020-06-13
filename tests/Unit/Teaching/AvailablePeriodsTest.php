<?php


namespace Tests\Unit\Teaching;


use App\Calendar\TimePeriod;
use App\Teaching\AvailablePeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AvailablePeriodsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function can_be_converted_to_time_period()
    {
        $available_period = factory(AvailablePeriod::class)->create([
            "starts" => 1000,
            'ends' => 1200,
        ]);

        $this->assertInstanceOf(TimePeriod::class, $available_period->timePeriod());
        $this->assertEquals("10:00", $available_period->timePeriod()->start->timeString);
        $this->assertEquals("12:00", $available_period->timePeriod()->end->timeString);
    }
}
