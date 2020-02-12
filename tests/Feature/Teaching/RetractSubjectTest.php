<?php


namespace Tests\Feature\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetractSubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function retract_a_public_subject()
    {
        $this->withoutExceptionHandling();

        $subject = factory(Subject::class)->state('public')->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/public-subjects/{$subject->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('subjects', [
            'id'        => $subject->id,
            'is_public' => false
        ]);
    }
}
