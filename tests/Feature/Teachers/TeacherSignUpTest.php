<?php


namespace Tests\Feature\Teachers;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherSignUpTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function teacher_signs_up()
    {
        $this->withoutExceptionHandling();

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

        $this->assertDatabaseHas('teacher_inquiries', $teacherInfo);
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
    public function the_email_is_required_without_the_phone()
    {
        $this->assertFieldIsInvalid(['email' => null], ['phone' => '']);
    }

    /**
     *@test
     */
    public function the_phone_number_is_required_without_email()
    {
        $this->assertFieldIsInvalid(['phone' => ''], ['email' => null]);
    }

    /**
     *@test
     */
    public function the_email_must_be_valid()
    {
        $this->assertFieldIsInvalid(['email' => 'not-a-valid-email-address']);
    }

    private function assertFieldIsInvalid($field, $additional = [])
    {
        $valid = [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
            'age' => 'test age',
            'years_in_taiwan' => 'test years',
            'available_hours_per_week' => 'test hours',
            'teaching_experience' => 'test experience',
        ];

        $response = $this->asGuest()->postJson('/teachers/sign-up', array_merge($valid, $field, $additional));
        $response->assertStatus(422);

        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
