<?php


namespace Tests\Unit\Teaching;


use App\Profile;
use App\Teaching\UnavailablePeriod;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UnavailablePeriodsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function globally_scoped_to_upcoming()
    {
        $old_from = Carbon::today()->subMonth();
        $old_to = Carbon::today()->subMonth()->addDay();

        $past_from = Carbon::today()->subDays(2);
        $upcoming_to = Carbon::today()->addDays(3);

        $future_from = Carbon::today()->addWeek();
        $future_to = Carbon::today()->addWeek()->addDay();
        /**
         * @var Profile $teacher
         */
        $teacher = $this->createTeacher();

        $old = $teacher->setUnavailablePeriod($old_from, $old_to);
        $ongoing = $teacher->setUnavailablePeriod($past_from, $upcoming_to);
        $future = $teacher->setUnavailablePeriod($future_from, $future_to);

        $this->assertCount(2, $teacher->fresh()->unavailablePeriods);

        $teacher->fresh()->unavailablePeriods->each(
            fn($p) => $this->assertTrue(Carbon::now()->lessThanOrEqualTo($p->ends))
        );

    }

    /**
     *@test
     */
    public function set_unavailable_period_for_teacher()
    {
        $teacher = $this->createTeacher();

        $from = Carbon::tomorrow()->setHour(8)->setMinutes(0);
        $to = Carbon::tomorrow()
                    ->addDays(3)
                    ->setHour(12)
                    ->setMinutes(0);

        $period = $teacher->setUnavailablePeriod($from, $to);

        $this->assertInstanceOf(UnavailablePeriod::class, $period);
        $this->assertTrue($period->teacher->is($teacher));
        $this->assertTrue($period->starts->equalTo($from));
        $this->assertTrue($period->ends->equalTo($to));
    }

    /**
     *@test
     */
    public function presents_as_useful_array()
    {
        $period = factory(UnavailablePeriod::class)->create([
            'starts' => Carbon::today()->setHour(8)->setMinutes(30),
            'ends' => Carbon::tomorrow()->setHour(17),
        ]);

        $expected = [
            'id' => $period->id,
            'profile_id' => $period->profile_id,
            'teacher_name' => $period->teacher->name,
            'starts' => $period->starts->format('Y-m-d H:i:s'),
            'ends' => $period->ends->format('Y-m-d H:i:s'),
            'starts_date' => $period->starts->format('Y-m-d'),
            'ends_date' => $period->ends->format('Y-m-d'),
            'starts_time' => $period->starts->format('H:i'),
            'ends_time' => $period->ends->format('H:i'),
            'starts_pretty' => $period->starts->format('jS M, Y (H:i)'),
            'ends_pretty' => $period->ends->format('jS M, Y (H:i)'),
        ];

        $this->assertEquals($expected, $period->toArray());
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
        ]);
    }
}
