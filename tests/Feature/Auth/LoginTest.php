<?php

namespace Tests\Feature\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function login_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'test@test.test',
            'password' => Hash::make('password')
        ]);
        $this->assertFalse(Auth::check());

        $response = $this->post("/admin/login", [
            'email' => 'test@test.test',
            'password' => 'password'
        ]);
        $response->assertStatus(302);

        $this->assertTrue(Auth::check());
    }
}