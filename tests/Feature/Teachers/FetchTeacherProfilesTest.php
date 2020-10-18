<?php


namespace Tests\Feature\Teachers;


use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchTeacherProfilesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_teacher_profiles()
    {
        $this->withoutExceptionHandling();

        $admin = factory(User::class)->state('admin-only')->create();
        $teacherA = factory(User::class)->state('teacher-only')->create();
        $teacherB = factory(User::class)->state('teacher-only')->create();
        $admin_teacher = factory(User::class)->state('admin-teacher')->create();

        $admin_profile = factory(Profile::class)->create(['user_id' => $admin->id]);
        $teacherA_profile = factory(Profile::class)->create(['user_id' => $teacherA->id]);
        $teacherB_profile = factory(Profile::class)->create(['user_id' => $teacherB->id]);
        $admin_teacher_profile = factory(Profile::class)->create(['user_id' => $admin_teacher->id]);

        $response = $this->asAdmin()->getJson("/admin/api/profiles");
        $response->assertStatus(200);

        $fetched_profiles = $response->json();

        $this->assertCount(3, $fetched_profiles);

        $expected = [$teacherA_profile->toArray(), $teacherB_profile->toArray(), $admin_teacher_profile->toArray()];

        $this->assertEquals($expected, $fetched_profiles);
    }
}
