<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateUserInfoTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_user_name_and_email()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'name' => 'old name',
            'email' => 'old@test.test',
        ]);

        $response = $this->actingAs($user)->postJson("/admin/me", [
            'name' => 'new name',
            'email' => 'new@test.test',
        ]);
        $response->assertStatus(200);

        $response_data = $response->decodeResponseJson();

        $this->assertEquals($user->id, $response_data['id']);
        $this->assertEquals('new name', $response_data['name']);
        $this->assertEquals('new@test.test', $response_data['email']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'new@test.test',
            'name' => 'new name'
        ]);
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $user = factory(User::class)->create([
            'name' => 'old name',
            'email' => 'old@test.test',
        ]);

        $response = $this->actingAs($user)->postJson("/admin/me", [
            'name' => '',
            'email' => 'new@test.test',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'old@test.test',
            'name' => 'old name'
        ]);
    }

    /**
     *@test
     */
    public function the_email_is_required()
    {
        $user = factory(User::class)->create([
            'name' => 'old name',
            'email' => 'old@test.test',
        ]);

        $response = $this->actingAs($user)->postJson("/admin/me", [
            'name' => 'new name',
            'email' => '',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'old@test.test',
            'name' => 'old name'
        ]);
    }

    /**
     *@test
     */
    public function the_email_must_be_a_valid_email()
    {
        $user = factory(User::class)->create([
            'name' => 'old name',
            'email' => 'old@test.test',
        ]);

        $response = $this->actingAs($user)->postJson("/admin/me", [
            'name' => 'new name',
            'email' => 'not-a-real-email-address',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'old@test.test',
            'name' => 'old name'
        ]);
    }

    /**
     *@test
     */
    public function the_email_needs_to_be_unique()
    {
        factory(User::class)->create(['email' => 'used@test.test']);
        $user = factory(User::class)->create([
            'name' => 'old name',
            'email' => 'old@test.test',
        ]);

        $response = $this->actingAs($user)->postJson("/admin/me", [
            'name' => 'new name',
            'email' => 'used@test.test',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'old@test.test',
            'name' => 'old name'
        ]);
    }

    /**
     *@test
     */
    public function email_does_not_have_to_change()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'name' => 'old name',
            'email' => 'old@test.test',
        ]);

        $response = $this->actingAs($user)->postJson("/admin/me", [
            'name' => 'new name',
            'email' => 'old@test.test',
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'old@test.test',
            'name' => 'new name'
        ]);
    }
}