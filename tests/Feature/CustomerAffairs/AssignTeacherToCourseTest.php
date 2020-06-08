<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AssignTeacherToCourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function assign_teacher_to_course()
    {
        $this->withoutExceptionHandling();

        $course = factory(Course::class)->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/courses/{$course->id}/teacher", [
            'profile_id' => $teacher->id,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'profile_id' => $teacher->id,
        ]);
    }

    /**
     * @test
     */
    public function the_profile_id_must_exist_on_profile_table()
    {
        $course = factory(Course::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/courses/{$course->id}/teacher", [
            'profile_id' => 99,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('profile_id');
    }
}
