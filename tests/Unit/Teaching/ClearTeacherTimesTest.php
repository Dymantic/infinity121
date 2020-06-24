<?php


namespace Tests\Unit\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearTeacherTimesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function clear_contained_time()
    {
        /**
         * @var $teacher Profile
         */
        $teacher = $this->createTeacher();

        $originalDay = new Day(3, [
            new TimePeriod('09:00', '13:00'),
            new TimePeriod('14:00', '17:00'),
        ]);
        $teacher->setAvailabilityForDay($originalDay);

        $teacher->clearTimeBlock([
            'day_of_week' => 3,
            'starts' => '11:00',
            'ends' => '12:30',
        ]);


        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn ($period) => $period->day_of_week === 3 &&
                        $period->starts === 900 &&
                        $period->ends === 1100)
                );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn ($period) => $period->day_of_week === 3 &&
                        $period->starts === 1230 &&
                        $period->ends === 1300)
        );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn ($period) => $period->day_of_week === 3 &&
                        $period->starts === 1400 &&
                        $period->ends === 1700)
        );
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
    }
}
