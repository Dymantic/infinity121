<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTeacherTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
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
     *@test
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
}
