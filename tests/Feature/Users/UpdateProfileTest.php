<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_teacher_may_update_their_profile()
    {
        $this->withoutExceptionHandling();
        $this->assertTrue(app()->getLocale() === 'en');

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher->makeProfile();
        $profile_id = $teacher->fresh()->profile->id;

        $profile_data = [
            'name' => 'Test Profile Name',
            'bio' => ['en' => 'Test english bio'],
            'nationality' => 'test_nationality',
            'qualifications' => 'test qualification',
            'years_experience' => 5,
            'chinese_ability' => 4,
            'teaching_specialties' => 'ESL',
        ];

        $response = $this
            ->actingAs($teacher)
            ->postJson("/admin/profiles/{$profile_id}", $profile_data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'name' => 'Test Profile Name',
            'nationality' => 'test_nationality',
            'qualifications' => 'test qualification',
            'years_experience' => 5,
            'chinese_ability' => 4,
            'teaching_specialties' => 'ESL',
        ]);

        $this->assertDatabaseHasWithTranslations('en', 'profiles', [
            'bio' => 'Test english bio',
        ]);

    }
}