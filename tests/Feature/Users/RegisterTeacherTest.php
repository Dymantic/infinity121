<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTeacherTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function register_a_teacher()
    {
        $this->withoutExceptionHandling();

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/users/teachers", $this->validAttributes());
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'name' => 'test teacher',
            'email' => 'test@test.test',
            'is_admin' => false,
            'is_teacher' => true,
        ]);

        $teacher = User::where('email', 'test@test.test')->first();
        $this->assertTrue(Hash::check('secret', $teacher->password));
    }

    /**
     *@test
     */
    public function only_registered_by_admin()
    {
        $teacher = factory(User::class)->states('teacher-only')->create();
        $response = $this
            ->actingAs($teacher)
            ->postJson("/admin/api/users/teachers", $this->validAttributes());
        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => '']);
    }

    /**
     *@test
     */
    public function the_email_is_required()
    {
        $this->assertFieldIsInvalid(['email' => '']);
    }

    /**
     *@test
     */
    public function the_email_should_be_a_valid_email()
    {
        $this->assertFieldIsInvalid(['email' => 'not-a-valid-email']);
    }

    /**
     *@test
     */
    public function the_email_needs_to_be_unique()
    {
        factory(User::class)->create(['email' => 'used@test.test']);

        $this->assertFieldIsInvalid(['email' => 'used@test.test']);
    }

    /**
     *@test
     */
    public function the_password_is_required()
    {
        $this->assertFieldIsInvalid(['password' => '']);
    }

    /**
     *@test
     */
    public function the_password_is_at_least_six_characters()
    {
        $this->assertFieldIsInvalid(['password' => 'short']);
    }

    /**
     *@test
     */
    public function the_password_must_be_confirmed()
    {
        $this->assertFieldIsInvalid(['password' => 'secret', 'password_confirmation' => 'bad']);
    }

    private function assertFieldIsInvalid($field)
    {
        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/users/teachers", array_merge($this->validAttributes(), $field));
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(array_keys($field)[0]);
    }

    private function validAttributes()
    {
        return [
            'name' => 'test teacher',
            'email' => 'test@test.test',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];
    }
}
