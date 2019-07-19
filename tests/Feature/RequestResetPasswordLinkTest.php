<?php


namespace Tests\Feature;


use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RequestResetPasswordLinkTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function have_reset_link_notification_sent()
    {
        Notification::fake();
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(['email' => 'test@test.test']);

        $response = $this->post("/password/email", ['email' => 'test@test.test']);
        $response->assertStatus(302);

        Notification::assertSentTo(
            $user,
            ResetPassword::class
        );
    }
}