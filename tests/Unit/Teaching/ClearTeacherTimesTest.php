<?php


namespace Tests\Unit\Teaching;


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
//        $teacher = $this->createTeacher();
//        $teacher->setAvailabilityFor(3, [
//            new TimePeriod('0900', '1300'),
//            new TimePeriod('1400', '1700')
//        ]);

//        $teacher->clearTimeBlock([
//            'day_of_week' => 3,
//            'starts' => '11:00',
//            'ends' => '12:30',
//        ]);
//
//        $this->assertTrue($teacher->availablePeriods->contains(fn ($period) => $period->day_of_week === 3 && $period->starts === 900 && $period->ends === 1100));
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
    }
}
