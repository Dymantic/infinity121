<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class RemoveUserTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function remove_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/users/{$user->id}");
        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'removed' => true,
        ]);
    }
}
