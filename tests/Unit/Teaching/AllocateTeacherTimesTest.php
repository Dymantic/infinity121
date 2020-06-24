<?php


namespace Tests\Unit\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AllocateTeacherTimesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function allocate_time_blocks_back_to_teacher()
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

        $teacher->allocateTimeBlock([
            'day_of_week' => 3,
            'starts' => '13:00',
            'ends' => '13:30',
        ]);

        $teacher->allocateTimeBlock([
            'day_of_week' => 3,
            'starts' => '18:00',
            'ends' => '20:00',
        ]);


        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn ($period) => $period->day_of_week === 3 &&
                        $period->starts === 900 &&
                        $period->ends === 1330)
        );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn ($period) => $period->day_of_week === 3 &&
                        $period->starts === 1400 &&
                        $period->ends === 1700)
        );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn ($period) => $period->day_of_week === 3 &&
                        $period->starts === 1800 &&
                        $period->ends === 2000)
        );
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
    }
}
