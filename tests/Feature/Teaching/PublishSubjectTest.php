<?php


namespace Tests\Feature\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishSubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function mark_a_private_subject_as_public()
    {
        $this->withoutExceptionHandling();

        $subject = factory(Subject::class)->state('private')->create();

        $response = $this->asAdmin()->postJson("/admin/api/public-subjects", [
            'subject_id' => $subject->id,
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('subjects', [
            'id'        => $subject->id,
            'is_public' => true,
        ]);
    }
}
