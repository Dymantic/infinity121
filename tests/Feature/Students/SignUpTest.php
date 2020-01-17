<?php

namespace Tests\Feature\Students;

use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function student_signs_up()
    {
        $this->withoutExceptionHandling();

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

        $this->assertDatabaseHas('student_inquiries', $studentInfo);
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => '']);
    }

    /**
     *@test
     */
    public function the_email_must_be_valid()
    {
        $this->assertFieldIsInvalid(['email' => 'not-valid-email-address']);
    }

    /**
     *@test
     */
    public function the_email_is_required_without_the_phone_number()
    {
        $this->assertFieldIsInvalid(['email' => ''], ['phone' => '']);
    }

    /**
     *@test
     */
    public function the_phone_is_required_without_the_email()
    {
        $this->assertFieldIsInvalid(['phone' => ''], ['email' => '']);
    }

    /**
     *@test
     */
    public function the_subject_id_is_required()
    {
        $this->assertFieldIsInvalid(['subject_id' => '']);
    }

    /**
     *@test
     */
    public function the_subject_id_must_exist_in_db()
    {
        $this->assertNull(Subject::find(99));

        $this->assertFieldIsInvalid(['subject_id' => 99]);
    }



    private function assertFieldIsInvalid($field, $additional = [])
    {
        $subject = factory(Subject::class)->create();
        $valid = [
            'name' => 'test name',
            'phone' => 'test phone',
            'email' => 'test@test.test',
            'age' => 'test age',
            'english_ability' => 'none',
            'address' => '123 test street, test city',
            'subject_id' => $subject->id,
            'message' => 'test message',
        ];

        $response = $this->asGuest()->postJson('students/sign-up', array_merge($valid, $field, $additional));
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
