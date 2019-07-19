<?php


namespace Tests\Feature\Auth;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function logout_a_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user);
        $this->assertTrue(Auth::check());

        $response = $this->post("/admin/logout", []);
        $response->assertRedirect("/");

        $this->assertFalse(Auth::check());
    }
}