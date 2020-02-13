<?php


namespace Tests\Feature\Teachers;


use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateTeacherProfilesCantBeViewedTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function private_profile_gets_four_oh_four()
    {
        $this->withoutExceptionHandling();
        $this->refreshApplicationWithLocale('en');

        $profile = factory(Profile::class)->state('private')->create();

        $response = $this->asGuest()->get("/en/teachers/{$profile->slug}");
        $response->assertStatus(404);
    }
}
