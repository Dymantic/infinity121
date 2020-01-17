<?php


namespace Tests\Feature\Profiles;


use App\Profile;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssignSubjectsToProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function assign_subjects_to_a_teacher_profile()
    {
        $this->withoutExceptionHandling();

        $profile = factory(Profile::class)->create();

        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/profiles/{$profile->id}/subjects", [
            'subject_ids' => [$subjectA->id, $subjectB->id],
        ]);
        $response->assertStatus(200);

        $profile_subjects = $profile->fresh()->subjects;

        $this->assertCount(2, $profile_subjects);

        $this->assertTrue($profile_subjects->contains($subjectA));
        $this->assertTrue($profile_subjects->contains($subjectB));
    }

    /**
     *@test
     */
    public function empty_array_of_subject_ids_is_allowed()
    {
        $this->withoutExceptionHandling();

        $profile = factory(Profile::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/profiles/{$profile->id}/subjects", [
            'subject_ids' => [],
        ]);
        $response->assertStatus(200);

        $profile_subjects = $profile->fresh()->subjects;

        $this->assertCount(0, $profile_subjects);
    }

    /**
     *@test
     */
    public function the_subject_ids_are_required()
    {
        $this->assertSubjectIdsValueIsInvalid(null);
    }

    /**
     *@test
     */
    public function the_subject_ids_must_be_an_array()
    {
        $subject = factory(Subject::class)->create();

        $this->assertSubjectIdsValueIsInvalid($subject->id);
    }

    /**
     *@test
     */
    public function the_subject_ids_must_be_of_existing_subjects()
    {
        $this->assertNull(Subject::find(99));

        $this->assertSubjectIdsValueIsInvalid([99]);
    }

    private function assertSubjectIdsValueIsInvalid($subject_ids)
    {
        $profile = factory(Profile::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/profiles/{$profile->id}/subjects", [
            'subject_ids' => $subject_ids,
        ]);
        $response->assertStatus(422);
    }
}
