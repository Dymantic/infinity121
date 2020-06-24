<?php


namespace Tests\Feature\CustomerAffairs;


use App\Calendar\DateFormatter;
use App\CustomerAffairs\Lesson;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LogLessonTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function log_a_completed_lesson()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $lesson = factory(Lesson::class)->state('due')->create([
            'lesson_date' => Carbon::yesterday(),
        ]);

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/lessons/{$lesson->id}/log", [
            'completed_on'    => Carbon::yesterday()->format(DateFormatter::STANDARD),
            'actual_start'    => '11:00',
            'actual_end'      => '12:00',
            'teacher_log'     => 'test log',
            'student_report'  => 'test report',
            'material_taught' => 'test material'
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('lessons', [
            'id'              => $lesson->id,
            'complete'        => true,
            'status'          => Lesson::STATUS_DONE,
            'completed_on'    => Carbon::yesterday(),
            'actual_start'    => '11:00',
            'actual_end'      => '12:00',
            'teacher_log'     => 'test log',
            'student_report'  => 'test report',
            'material_taught' => 'test material',
            'profile_id'      => $teacher->id,
        ]);
    }

    /**
     *@test
     */
    public function the_completed_on_date_is_required()
    {
        $this->assertFieldIsInvalid(['completed_on' => null]);
    }

    /**
     *@test
     */
    public function the_completed_on_must_be_a_valid_date()
    {
        $this->assertFieldIsInvalid(['completed_on' => 'not-a-date-at-all']);
    }

    /**
     *@test
     */
    public function the_actual_start_is_required()
    {
        $this->assertFieldIsInvalid(['actual_start' => null]);
    }

    /**
     *@test
     */
    public function the_actual_start_must_be_a_valid_time()
    {
        $this->assertFieldIsInvalid(['actual_start' => 'this-aint-no-time']);
    }

    /**
     *@test
     */
    public function the_actual_end_is_required()
    {
        $this->assertFieldIsInvalid(['actual_end' => null]);
    }

    /**
     *@test
     */
    public function the_actual_end_must_be_a_real_time()
    {
        $this->assertFieldIsInvalid(['actual_end' => 'not-a-real-time']);
    }

    /**
     *@test
     */
    public function the_end_time_must_come_after_the_start()
    {
        $this->assertFieldIsInvalid(['actual_end' => '10:00', 'actual_start' => '12:00']);
    }

    private function assertFieldIsInvalid($field)
    {
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $lesson = factory(Lesson::class)->state('due')->create([
            'lesson_date' => Carbon::yesterday(),
        ]);

        $valid = [
            'completed_on'    => Carbon::yesterday()->format(DateFormatter::STANDARD),
            'actual_start'    => '11:00',
            'actual_end'      => '12:00',
            'teacher_log'     => 'test log',
            'student_report'  => 'test report',
            'material_taught' => 'test material'
        ];

        $response = $this
            ->actingAs($teacher->user)
            ->postJson("/admin/api/lessons/{$lesson->id}/log", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
