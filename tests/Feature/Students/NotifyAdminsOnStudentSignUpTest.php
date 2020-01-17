<?php


namespace Tests\Feature\Students;


use App\Notifications\StudentSignedUp;
use App\Teaching\Subject;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotifyAdminsOnStudentSignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function notifies_admins_on_student_sign_up()
    {
        Notification::fake();

        $this->withoutExceptionHandling();

        $adminA = factory(User::class)->state('admin-only')->create();
        $adminB = factory(User::class)->state('admin-only')->create();
        $teacher = factory(User::class)->state('teacher-only')->create();
        $subject = factory(Subject::class)->create();

        $studentInfo = [
            'name' => 'test name',
            'phone' => 'test phone',
            'email' => 'test@test.test',
            'age' => 'test age',
            'english_ability' => 'none',
            'address' => '123 test street, test city',
            'subject_id' => $subject->id,
            'message' => 'test message',
        ];
        $response = $this->asGuest()->postJson('/students/sign-up', $studentInfo);
        $response->assertStatus(201);

        Notification::assertSentTo([$adminA, $adminB], StudentSignedUp::class, function($notification) {
            return $notification->inquiry->name === 'test name';
        });

        Notification::assertNotSentTo($teacher, StudentSignedUp::class);
    }
}
