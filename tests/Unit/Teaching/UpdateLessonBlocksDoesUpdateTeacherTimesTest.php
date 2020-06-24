<?php


namespace Tests\Unit\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\CustomerAffairs\Course;
use App\Profile;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateLessonBlocksDoesUpdateTeacherTimesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function updating_an_existing_courses_leson_blocks_changes_teacher_times()
    {
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->create()
        ]);

        $teacher->setAvailabilityForDay(new Day(Carbon::WEDNESDAY, [
            new TimePeriod("10:00", "12:00")
        ]));

        $course = factory(Course::class)->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => Carbon::WEDNESDAY,
                'starts'      => '11:00',
                'ends'        => '12:00',
            ]
        ]);
        $course->assignTeacher($teacher->id);

        $this->assertEquals(
            [
                'periods' => [
                    [
                        'starts' => '10:00',
                        'ends'   => '11:00'
                    ]
                ]
            ],
            $teacher->currentSchedule()['available'][Carbon::WEDNESDAY]
        );

        $course->setLessonBlocks([
            [
                'day_of_week' => Carbon::WEDNESDAY,
                'starts'      => '10:00',
                'ends'        => '11:00',
            ]
        ]);

        $this->assertEquals(
            [
                'periods' => [
                    [
                        'starts' => '11:00',
                        'ends'   => '12:00'
                    ]
                ]
            ],
            $teacher->fresh()->currentSchedule()['available'][Carbon::WEDNESDAY]
        );


    }
}
