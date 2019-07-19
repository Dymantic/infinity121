<?php


namespace Tests\Feature\Users;


use App\Notifications\AdminRegistered;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyNewAdminsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function notify_registered_admin()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/users/admins", [
            'name' => 'test admin',
            'email' => 'admin@test.test',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $response->assertStatus(201);

        $user = User::where('email', 'admin@test.test')->first();

        Notification::assertSentTo(
            $user,
            AdminRegistered::class,
            function($notification, $channels) {
                return $notification->admin->name === 'test admin' && $notification->password === 'secret';
            }
        );
    }
}