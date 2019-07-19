<?php


namespace Tests\Feature\Users;


use App\Notifications\TeacherRegistered;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyNewTeacherTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function notify_a_newly_registered_teacher()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/users/teachers", [
            'name' => 'test teacher',
            'email' => 'teacher@test.test',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);
        $response->assertStatus(201);

        $teacher = User::where('email', 'teacher@test.test')->first();

        Notification::assertSentTo(
            $teacher,
            TeacherRegistered::class,
            function($notification) {
                return $notification->teacher->name === 'test teacher' && $notification->password === 'secret';
            }
        );
    }
}