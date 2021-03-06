<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchUsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function all_users_may_be_fetched()
    {
        $bob = factory(User::class)->state('teacher-only')->create();
        $alice = factory(User::class)->state('admin-only')->create();
        $mike = factory(User::class)->state('admin-teacher')->create();

        $this->withoutExceptionHandling();

        $response = $this->actingAs($mike)->getJson("/admin/api/users");
        $response->assertStatus(200);

        $fetched_users = $response->json();

        $this->assertCount(3, $fetched_users);

        $this->assertEquals(collect($fetched_users)->pluck('id')->values()->all(), [$bob->id, $alice->id, $mike->id]);

    }

    /**
     *@test
     */
    public function only_admin_can_fetch_users()
    {
        $bob = factory(User::class)->state('teacher-only')->create();
        factory(User::class)->state('admin-teacher')->create();

        $response = $this->actingAs($bob)->getJson("/admin/api/users");
        $response->assertStatus(403);
    }
}
