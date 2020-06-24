<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CourseLessonsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function can_set_next_lesson_when_just_confirmed()
    {
        /**
         * @var Course $course
         */
        $course = factory(Course::class)->state('unconfirmed')->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => 5,
                'starts' => '16:00',
                'ends' => '17:00',
            ],
        ]);
        $course->confirm(Carbon::today()->addWeek()->startOfWeek());

        $lesson = $course->setNextLesson();

        $this->assertTrue(
            Carbon::today()
                  ->addWeek()
                  ->startOfWeek()
                  ->next('Friday')
                  ->isSameDay($lesson->lesson_date)
        );
        $this->assertEquals("16:00", $lesson->starts);
        $this->assertEquals("17:00", $lesson->ends);
        $this->assertEquals($course->id, $lesson->course_id);
        $this->assertFalse($lesson->fresh()->complete);
    }

    /**
     *@test
     */
    public function can_get_next_upcoming_lesson()
    {
        /**
         * @var Course $course
         */
        $course = factory(Course::class)->state('unconfirmed')->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => 5,
                'starts' => '16:00',
                'ends' => '17:00',
            ],
        ]);
        $course->confirm(Carbon::today()->addWeek()->startOfWeek());

        $lesson = $course->setNextLesson();

        $this->assertTrue($course->nextLesson()->is($lesson));
    }
}
