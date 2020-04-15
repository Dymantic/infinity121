<?php


namespace Tests\Feature\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetSubjectsOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_subjects_position_by_posting_array()
    {
        $this->withoutExceptionHandling();

        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();
        $subjectC = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/subjects-order", [
            'subject_ids' => [$subjectB->id, $subjectC->id, $subjectA->id]
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('subjects', [
            'id' => $subjectB->id,
            'position' => 1
        ]);

        $this->assertDatabaseHas('subjects', [
            'id' => $subjectC->id,
            'position' => 2
        ]);

        $this->assertDatabaseHas('subjects', [
            'id' => $subjectA->id,
            'position' => 3
        ]);
    }

    /**
     *@test
     */
    public function the_subject_ids_are_required()
    {
        $response = $this->asAdmin()->postJson("/admin/api/subjects-order", [
            'subject_ids' => null
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subject_ids');

    }

    /**
     *@test
     */
    public function the_subject_ids_must_be_an_array()
    {
        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/subjects-order", [
            'subject_ids' => $subject->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subject_ids');
    }

    /**
     *@test
     */
    public function the_subject_ids_must_be_for_existing_subjects()
    {
        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/subjects-order", [
            'subject_ids' => [$subject->id, 99],
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('subject_ids.1');
    }
}
