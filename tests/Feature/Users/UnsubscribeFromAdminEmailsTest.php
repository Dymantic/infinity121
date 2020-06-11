<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnsubscribeFromAdminEmailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function unsubscribe_from_admin_emails()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->state('admin-only')->create([
            'receive_admin_emails' => true
        ]);

        $response = $this->asAdmin()->deleteJson("/admin/api/admin-email-subscriptions/{$user->id}");
        $response->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id'                   => $user->id,
            'receive_admin_emails' => false,
        ]);
    }
}
