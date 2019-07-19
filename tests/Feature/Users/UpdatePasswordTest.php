<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_logged_in_user_updates_password()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $response = $this->actingAs($user)->postJson("/admin/me/password", [
            'old_password' => 'password',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secret',
        ]);
        $response->assertStatus(200);

        $this->assertTrue(Hash::check('secret', $user->fresh()->password));
    }

    /**
     *@test
     */
    public function the_old_password_is_required()
    {
        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $response = $this->actingAs($user)->postJson("/admin/me/password", [
            'old_password' => '',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secret',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('old_password');
    }

    /**
     *@test
     */
    public function the_old_password_must_match_the_current_users_password()
    {
        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $response = $this->actingAs($user)->postJson("/admin/me/password", [
            'old_password' => 'NONSENSE',
            'new_password' => 'secret',
            'new_password_confirmation' => 'secret',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('old_password');
    }

    /**
     *@test
     */
    public function the_new_password_is_required()
    {
        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $response = $this->actingAs($user)->postJson("/admin/me/password", [
            'old_password' => 'password',
            'new_password' => '',
            'new_password_confirmation' => 'secret',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('new_password');
    }

    /**
     *@test
     */
    public function the_new_password_must_be_at_least_six_characters()
    {
        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $response = $this->actingAs($user)->postJson("/admin/me/password", [
            'old_password' => 'password',
            'new_password' => 'short',
            'new_password_confirmation' => 'short',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('new_password');
    }

    /**
     *@test
     */
    public function the_new_password_must_be_confirmed()
    {
        $user = factory(User::class)->create(['password' => Hash::make('password')]);

        $response = $this->actingAs($user)->postJson("/admin/me/password", [
            'old_password' => 'password',
            'new_password' => 'secret',
            'new_password_confirmation' => 'does-not-match',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('new_password');
    }
}