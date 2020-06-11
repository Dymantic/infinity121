<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ConfirmCourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function mark_a_course_as_confirmed()
    {
        $this->withoutExceptionHandling();

        /**
         * @var Course $course
         */
        $course = factory(Course::class)->state('unconfirmed')->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => 4,
                'starts' => '16:00',
                'ends' => '18:00',
            ]
        ]);


        $response = $this->asAdmin()->postJson("/admin/api/confirmed-courses", [
            'course_id' => $course->id,
            'starts_from' => Carbon::today()->addWeek()->startOfWeek()->format('Y-m-d'),
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'starts_from' => Carbon::today()->addWeek()->startOfWeek(),
            'confirmed_on' => Carbon::today(),
        ]);

        $this->assertDatabaseHas('lessons', [
            'course_id' => $course->id,
            'lesson_date' => Carbon::today()->addWeek()->startOfWeek()->next('Thursday')->setHour(16)->setMinutes(0),
            'complete' => false,
        ]);
    }

    /**
     *@test
     */
    public function the_course_id_must_exist_in_courses_table()
    {
        $this->assertFieldInvalid(['course_id' => 999]);
    }

    /**
     *@test
     */
    public function the_starts_from_date_is_required()
    {
        $this->assertFieldInvalid(['starts_from' => null]);
    }

    /**
     *@test
     */
    public function the_starts_from_date_must_be_a_real_date()
    {
        $this->assertFieldInvalid(['starts_from' => 'not-a-real-date']);
    }

    private function assertFieldInvalid($field)
    {
        $course = factory(Course::class)->state('unconfirmed')->create();

        $valid = [
            'course_id' => $course->id,
            'starts_from' => Carbon::today()->addWeek()->format('Y-m-d'),
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/confirmed-courses", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
