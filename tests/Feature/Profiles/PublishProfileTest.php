<?php


namespace Tests\Feature\Profiles;


use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function publish_a_profile()
    {
        $this->withoutExceptionHandling();

        $profile = factory(Profile::class)->state('private')->create();

        $response = $this->asAdmin()->postJson('/admin/api/published-profiles', [
            'profile_id' => $profile->id
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'is_public' => true,
        ]);
    }

    /**
     *@test
     */
    public function a_teacher_cannot_publish_a_profile()
    {

        $profile = factory(Profile::class)->state('private')->create();

        $response = $this->asTeacher()->postJson('/admin/api/published-profiles', [
            'profile_id' => $profile->id
        ]);
        $response->assertStatus(403);
    }


}
