<?php


namespace Tests\Unit\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\CustomerAffairs\Course;
use App\Profile;
use App\Teaching\AvailablePeriod;
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

        $day = new Day(Carbon::MONDAY, [$periodA, $periodB]);

        $teacher->setAvailabilityForDay($day);

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

        $originalDay = new Day(Carbon::MONDAY, [$periodC]);
        $newDay = new Day(Carbon::MONDAY, [$periodA, $periodB]);



        $teacher->setAvailabilityForDay($originalDay);

        $this->assertCount(1, $teacher->fresh()->availablePeriods);

        $teacher->setAvailabilityForDay($newDay);

        $currently_available = $teacher->fresh()->availablePeriods;
        $this->assertCount(2, $currently_available);

        $currently_available->each(fn ($period) => $this->assertNotEquals(1400, $period->starts));
    }

    /**
     *@test
     */
    public function setting_periods_available_will_respect_assigned_times()
    {
        $teacher = $this->createTeacher();
        $teacher->setAvailabilityForDay(new Day(Carbon::WEDNESDAY, [
            new TimePeriod("8:00", "10:00")
        ]));

        $course = factory(Course::class)->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => Carbon::WEDNESDAY,
                'starts' => "10:00",
                'ends' => '12:00',
            ]
        ]);

        $course->assignTeacher($teacher->id);


        $teacher->fresh()->setAvailabilityForDay(new Day(Carbon::WEDNESDAY, [
            new TimePeriod("8:00", "10:00"),
            new TimePeriod("10:00", "14:00"),
        ]));


        $teacher = $teacher->fresh();

        $this->assertCount(2, $teacher->fresh()->availablePeriods);
        $this->assertTrue($teacher->availablePeriods->contains(
            fn (AvailablePeriod $p) => $p->day_of_week === Carbon::WEDNESDAY &&
                $p->starts === 800 && $p->ends === 1000
        ));
        $this->assertTrue($teacher->availablePeriods->contains(
            fn (AvailablePeriod $p) => $p->day_of_week === Carbon::WEDNESDAY &&
                $p->starts === 1200 && $p->ends === 1400
        ));
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
