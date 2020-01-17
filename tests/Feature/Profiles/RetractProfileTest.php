<?php


namespace Tests\Feature\Profiles;


use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetractProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function retract_a_profile()
    {
        $this->withoutExceptionHandling();

        $profile = factory(Profile::class)->state('public')->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/published-profiles/{$profile->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'id'        => $profile->id,
            'is_public' => false,
        ]);
    }

    /**
     *@test
     */
    public function a_teacher_cannot_retract_a_profile()
    {
        $profile = factory(Profile::class)->state('public')->create();

        $response = $this->asTeacher()->deleteJson("/admin/api/published-profiles/{$profile->id}");
        $response->assertStatus(403);

        $this->assertDatabaseHas('profiles', [
            'id'        => $profile->id,
            'is_public' => true,
        ]);
    }
}
