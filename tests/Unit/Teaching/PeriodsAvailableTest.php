<?php


namespace Tests\Unit\Teaching;


use App\Calendar\TimePeriod;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PeriodsAvailableTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_periods_available_for_a_teacher()
    {
        $teacher = $this->createTeacher();

        $periodA = new TimePeriod("09:00","12:00");
        $periodB = new TimePeriod("16:00","20:00");

        $teacher->setAvailabilityFor(Carbon::MONDAY, [$periodA, $periodB]);

        $available_periods = $teacher->fresh()->availablePeriods;

        $this->assertCount(2, $available_periods);

        $available_periods->each(function($period) use ($teacher) {
            $this->assertEquals($teacher->id, $period->profile_id);
            $this->assertEquals(Carbon::MONDAY, $period->day_of_week);
            $this->assertContains($period->starts, [900, 1600]);
            $this->assertContains($period->ends, [1200, 2000]);
        });
    }

    /**
     *@test
     */
    public function setting_available_periods_for_a_day_removes_existing_ones()
    {
        $teacher = $this->createTeacher();

        $periodA = new TimePeriod("09:00","12:00");
        $periodB = new TimePeriod("16:00","20:00");
        $periodC = new TimePeriod("14:00","19:00");

        $teacher->setAvailabilityFor(Carbon::MONDAY, [$periodC]);

        $this->assertCount(1, $teacher->fresh()->availablePeriods);

        $teacher->setAvailabilityFor(Carbon::MONDAY, [$periodA, $periodB]);

        $currently_available = $teacher->fresh()->availablePeriods;
        $this->assertCount(2, $currently_available);

        $currently_available->each(fn ($period) => $this->assertNotEquals(1400, $period->starts));
    }



    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
    }

    private function createTeacherWithPeriods($day, $periods)
    {
        $teacher = $this->createTeacher();
        $time_periods = collect($periods)
            ->map(fn($times) => new TimePeriod($times[0], $times[1]))
            ->all();
        $teacher->setAvailabilityFor($day, $time_periods);

        return $teacher;
    }
}
