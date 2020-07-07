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

class CancelLessonTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function cancel_a_lesson()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $lesson = factory(Lesson::class)->state('due')->create([
            'lesson_date' => Carbon::yesterday(),
        ]);

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/cancelled-lessons", [
            'lesson_id'    => $lesson->id,
            'reason' => 'test reason'
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('lessons', [
            'id'                    => $lesson->id,
            'complete'              => true,
            'status'                => Lesson::STATUS_CANCELLED,
            'completed_on'          => Carbon::yesterday(),
            'actual_start'          => $lesson->starts,
            'actual_end'            => $lesson->ends,
            'teacher_log'           => 'test reason',
            'material_taught'       => null,
            'student_interaction'   => null,
            'student_comprehension' => null,
            'student_confidence'    => null,
            'student_output'        => null,
            'profile_id'            => $teacher->id,
        ]);
    }

    /**
     *@test
     */
    public function the_reason_is_required()
    {
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $lesson = factory(Lesson::class)->state('due')->create([
            'lesson_date' => Carbon::yesterday(),
        ]);

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/cancelled-lessons", [
            'lesson_id'    => $lesson->id,
            'reason' => null
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('reason');
    }
}
