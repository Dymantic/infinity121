<?php


namespace Tests\Feature\Teaching;


use App\Profile;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteSubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_a_subject()
    {
        $this->withoutExceptionHandling();

        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/subjects/{$subject->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }

    /**
     *@test
     */
    public function profile_subject_pivot_records_are_deleted_when_subject_deleted()
    {
        $this->withoutExceptionHandling();

        $profile = factory(Profile::class)->create();
        $subject = factory(Subject::class)->create();
        $profile->subjects()->attach($subject);

        $response = $this->asAdmin()->deleteJson("/admin/api/subjects/{$subject->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('profile_subject', [
            'profile_id' => $profile->id,
            'subject_id' => $subject->id,
        ]);
    }
}
