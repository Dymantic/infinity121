<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SubscribeToAdminEmailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function subscribe_to_receive_admin_emails()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->state('admin-only')->create();

        $response = $this->asAdmin()->postJson("/admin/api/admin-email-subscriptions", [
            'user_id' => $user->id,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id'                 => $user->id,
            'receive_admin_emails' => true,
        ]);

    }

    /**
     *@test
     */
    public function the_user_id_must_exist()
    {
        $response = $this->asAdmin()->postJson("/admin/api/admin-email-subscriptions", [
            'user_id' => 999,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('user_id');
    }
}
