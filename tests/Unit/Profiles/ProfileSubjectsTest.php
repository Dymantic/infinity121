<?php


namespace Tests\Unit\Profiles;


use App\Profile;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileSubjectsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function subjects_can_be_assigned_to_a_profile()
    {
        $profile = factory(Profile::class)->create();

        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();

        $profile->assignSubjects([$subjectA->id, $subjectB->id]);

        $this->assertCount(2, $profile->fresh()->subjects);

        $this->assertTrue($profile->fresh()->subjects->contains($subjectA));
        $this->assertTrue($profile->fresh()->subjects->contains($subjectB));
    }

    /**
     *@test
     */
    public function assigning_subjects_overwrites_existing_subjects()
    {
        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();
        $subjectC = factory(Subject::class)->create();

        $profile = factory(Profile::class)->create();
        $profile->assignSubjects([$subjectA->id]);
        $this->assertCount(1, $profile->fresh()->subjects);
        $this->assertTrue($profile->fresh()->subjects->contains($subjectA));

        $profile->assignSubjects([$subjectB->id, $subjectC->id]);

        $this->assertCount(2, $profile->fresh()->subjects);

        $this->assertTrue($profile->fresh()->subjects->contains($subjectB));
        $this->assertTrue($profile->fresh()->subjects->contains($subjectC));
    }
}
