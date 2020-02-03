<?php


namespace Tests\Feature\Contact;


use App\Notifications\ContactMessage;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ContactMessageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function submit_contact_message()
    {
        Notification::fake();

        $admin = factory(User::class)->state('admin-only')->create();

        $this->withoutExceptionHandling();

        $response = $this->asGuest()->postJson("/contact", [
            'name' => 'test name',
            'email' => 'test@test.test',
            'message' => 'test message',
        ]);
        $response->assertStatus(200);

        Notification::assertSentTo($admin, ContactMessage::class, function($notification, $channel) {
            return $notification->message->name === 'test name';
        });
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        Notification::fake();

        $response = $this->asGuest()->postJson("/contact", [
            'name' => '',
            'email' => 'test@test.test',
            'message' => 'test message',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function the_email_is_required()
    {
        Notification::fake();

        $response = $this->asGuest()->postJson("/contact", [
            'name' => 'test name',
            'email' => '',
            'message' => 'test message',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    /**
     *@test
     */
    public function the_email_must_be_valid()
    {
        Notification::fake();

        $response = $this->asGuest()->postJson("/contact", [
            'name' => 'test name',
            'email' => 'not-a-valid-email',
            'message' => 'test message',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }
}
