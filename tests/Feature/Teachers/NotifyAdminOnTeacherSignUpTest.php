<?php


namespace Tests\Feature\Teachers;


use App\Notifications\TeacherSignedUp;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminOnTeacherSignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function admins_are_notified_of_new_teacher_sign_ups()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $adminA = factory(User::class)->state('admin-only')->create();
        $adminB = factory(User::class)->state('admin-only')->create();
        $teacher = factory(User::class)->state('teacher-only')->create();

        $teacherInfo = [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'age' => 'test age',
            'years_in_taiwan' => 'test years',
            'available_hours_per_week' => 'test hours',
            'teaching_experience' => 'test experience',
        ];
        $response = $this->asGuest()->postJson('/teachers/sign-up', $teacherInfo);
        $response->assertStatus(201);

        Notification::assertSentTo([$adminA, $adminB], TeacherSignedUp::class, function($notification) {
            return $notification->inquiry->name === 'test name';
        });

        Notification::assertNotSentTo($teacher, TeacherSignedUp::class);
    }
}
