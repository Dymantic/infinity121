<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SetLessonBlocksForCourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function set_lesson_blocks_for_a_course()
    {
        $this->withoutExceptionHandling();

        $course = factory(Course::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/courses/{$course->id}/lesson-blocks", [
            'blocks' => [
                [
                    'day_of_week' => 3,
                    'starts'      => '16:00',
                    'ends'        => '17:00',
                ],
                [
                    'day_of_week' => 5,
                    'starts'      => '18:00',
                    'ends'        => '20:00',
                ]
            ]
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('lesson_blocks', [
           'course_id' => $course->id,
           'day_of_week' => 3,
           'starts'      => '16:00',
           'ends'        => '17:00',
        ]);

        $this->assertDatabaseHas('lesson_blocks', [
            'course_id' => $course->id,
            'day_of_week' => 5,
            'starts'      => '18:00',
            'ends'        => '20:00',
        ]);
    }

    /**
     *@test
     */
    public function the_blocks_area_required()
    {
        $this->assertFieldIsInvalid(['blocks' => []]);
    }

    /**
     *@test
     */
    public function each_block_should_be_an_array()
    {
        $this->assertFieldIsInvalid(['blocks' => ['not-an-array']], 'blocks.0');
    }

    /**
     *@test
     */
    public function each_block_requires_a_day_of_week()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => null,
                'starts'      => '18:00',
                'ends'        => '20:00',
            ]
        ]], 'blocks.0.day_of_week');
    }

    /**
     *@test
     */
    public function day_of_week_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 'not-an-integer',
                'starts'      => '18:00',
                'ends'        => '20:00',
            ]
        ]], 'blocks.0.day_of_week');
    }

    /**
     *@test
     */
    public function day_of_week_must_be_zero_or_greater()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => -1,
                'starts'      => '18:00',
                'ends'        => '20:00',
            ]
        ]], 'blocks.0.day_of_week');
    }

    /**
     *@test
     */
    public function day_of_week_must_be_six_or_less()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 7,
                'starts'      => '18:00',
                'ends'        => '20:00',
            ]
        ]], 'blocks.0.day_of_week');
    }

    /**
     *@test
     */
    public function the_starts_time_is_required()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 1,
                'starts'      => null,
                'ends'        => '20:00',
            ]
        ]], 'blocks.0.starts');
    }

    /**
     *@test
     */
    public function the_start_time_must_be_a_valid_time()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 1,
                'starts'      => 'not-a-valid-time',
                'ends'        => '20:00',
            ]
        ]], 'blocks.0.starts');
    }

    /**
     *@test
     */
    public function the_end_time_is_required()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 1,
                'starts'      => '16:00',
                'ends'        => null,
            ]
        ]], 'blocks.0.ends');
    }

    /**
     *@test
     */
    public function the_end_time_must_be_a_valid_time()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 1,
                'starts'      => '16:00',
                'ends'        => 'not-a-real-time',
            ]
        ]], 'blocks.0.ends');
    }

    /**
     *@test
     */
    public function the_end_time_must_come_after_the_start_time()
    {
        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 1,
                'starts'      => '16:00',
                'ends'        => '6:00',
            ]
        ]], 'blocks.0.ends');

        $this->assertFieldIsInvalid(['blocks' => [
            [
                'day_of_week' => 1,
                'starts'      => '16:00',
                'ends'        => '15:00',
            ]
        ]], 'blocks.0.ends');
    }

    /**
     *@test
     */
    public function the_blocks_must_be_an_array()
    {
        $this->assertFieldIsInvalid(['blocks' => 'not-an-array']);
    }

    private function assertFieldIsInvalid($fields, $error_name = null)
    {
        $course = factory(Course::class)->create();

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/courses/{$course->id}/lesson-blocks", $fields);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors($error_name ?? array_key_first($fields));
    }
}
