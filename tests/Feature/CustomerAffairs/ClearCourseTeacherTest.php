<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClearCourseTeacherTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function remove_teacher_from_a_course()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
        ]);
        $course = factory(Course::class)->create();
        $course->assignTeacher($teacher->id);

        $response = $this->asAdmin()->deleteJson("/admin/api/courses/{$course->id}/teacher");
        $response->assertSuccessful();

        $this->assertNull($course->fresh()->teacher);
    }
}
