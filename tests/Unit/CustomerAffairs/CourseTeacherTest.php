<?php


namespace Tests\Unit\CustomerAffairs;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\CustomerAffairs\Course;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CourseTeacherTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function teacher_can_be_assigned_to_course()
    {
        $course = factory(Course::class)->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);

        $course->assignTeacher($teacher->id);

        $this->assertTrue($course->fresh()->teacher->is($teacher));
    }

    /**
     * @test
     */
    public function can_clear_course_teacher()
    {
        $course = factory(Course::class)->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $course->assignTeacher($teacher->id);

        $this->assertTrue($course->fresh()->teacher->is($teacher));

        $course->clearTeacher();

        $this->assertNull($course->fresh()->teacher);
    }

    /**
     * @test
     */
    public function assigning_course_clears_teachers_times()
    {
        $course = factory(Course::class)->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $teacher->setAvailabilityForDay(new Day(1, [new TimePeriod("10:00", "14:00")]));
        $teacher->setAvailabilityForDay(new Day(4, [new TimePeriod("9:00", "13:00")]));

        $course->setLessonBlocks([
            [
                'day_of_week' => 1,
                'starts'      => "11:00",
                "ends"        => "12:00",
            ],
            [
                'day_of_week' => 4,
                'starts'      => "11:00",
                "ends"        => "12:00",
            ]
        ]);

        $course->assignTeacher($teacher->id);


        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 1 &&
                        $period->starts === 1000 &&
                        $period->ends === 1100)
        );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 1 &&
                        $period->starts === 1200 &&
                        $period->ends === 1400)
        );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 4 &&
                        $period->starts === 900 &&
                        $period->ends === 1100)
        );

        $this->assertTrue(
            $teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 4 &&
                        $period->starts === 1200 &&
                        $period->ends === 1300)
        );
    }

    /**
     *@test
     */
    public function assigning_course_to_another_teacher_adds_availability_back_to_teacher()
    {

        $original_teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);

        $new_teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $original_teacher->setAvailabilityForDay(new Day(1, [new TimePeriod("10:00", "14:00")]));
        $original_teacher->setAvailabilityForDay(new Day(4, [new TimePeriod("9:00", "13:00")]));

        $course = factory(Course::class)->create([
            'profile_id' => $original_teacher->id,
        ]);

        $course->setLessonBlocks([
            [
                'day_of_week' => 1,
                'starts'      => "14:00",
                "ends"        => "15:00",
            ],
            [
                'day_of_week' => 4,
                'starts'      => "16:00",
                "ends"        => "18:00",
            ]
        ]);

        $course->assignTeacher($new_teacher->id);

//        dd($original_teacher->fresh()->availablePeriods->toArray());


        $this->assertTrue(
            $original_teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 1 &&
                        $period->starts === 1000 &&
                        $period->ends === 1500)
        );

        $this->assertTrue(
            $original_teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 4 &&
                        $period->starts === 900 &&
                        $period->ends === 1300)
        );

        $this->assertTrue(
            $original_teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 4 &&
                        $period->starts === 1600 &&
                        $period->ends === 1800)
        );
    }

    /**
     *@test
     */
    public function clearing_teacher_reallocates_time_to_teacher()
    {
        $new_teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);


        $course = factory(Course::class)->create([
            'profile_id' => $new_teacher->id,
        ]);

        $course->setLessonBlocks([
            [
                'day_of_week' => 1,
                'starts'      => "14:00",
                "ends"        => "15:00",
            ],
            [
                'day_of_week' => 4,
                'starts'      => "16:00",
                "ends"        => "18:00",
            ]
        ]);

        $course->clearTeacher();


        $this->assertTrue(
            $new_teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 1 &&
                        $period->starts === 1400 &&
                        $period->ends === 1500)
        );

        $this->assertTrue(
            $new_teacher
                ->fresh()
                ->availablePeriods->contains(
                    fn($period) => $period->day_of_week === 4 &&
                        $period->starts === 1600 &&
                        $period->ends === 1800)
        );

    }
}
