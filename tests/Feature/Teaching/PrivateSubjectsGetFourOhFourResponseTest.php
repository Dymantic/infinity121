<?php


namespace Tests\Feature\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrivateSubjectsGetFourOhFourResponseTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function cannot_view_page_of_subject_marked_private()
    {
        $this->refreshApplicationWithLocale('en');
        $subject = factory(Subject::class)->state('private')->create();

        $response = $this->followingRedirects()->asGuest()->get("/en/courses/{$subject->slug}");
        $response->assertStatus(404);
    }
}
