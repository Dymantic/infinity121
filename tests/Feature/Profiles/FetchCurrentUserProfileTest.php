<?php

namespace Tests\Feature\Profiles;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchCurrentUserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_the_current_users_profile()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher->makeProfile();

        $response = $this->actingAs($teacher)->getJson("/admin/api/me/profile");
        $response->assertStatus(200);

        $fetched_profile = $response->json();

        $this->assertEquals($teacher->fresh()->profile->toArray(), $fetched_profile);
    }
}
