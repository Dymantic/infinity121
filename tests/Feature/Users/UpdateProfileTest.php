<?php


namespace Tests\Feature\Users;


use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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
            'teaching_since' => 1999,
            'chinese_ability' => 4,
        ];

        $response = $this
            ->actingAs($teacher)
            ->postJson("/admin/profiles/{$profile_id}", $profile_data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'name' => 'Test Profile Name',
            'nationality' => 'test_nationality',
            'qualifications' => 'test qualification',
            'teaching_since' => 1999,
            'chinese_ability' => 4,
        ]);

        $this->assertDatabaseHasWithTranslations('en', 'profiles', [
            'bio' => 'Test english bio',
        ]);

    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => '']);
    }

    /**
     *@test
     */
    public function teaching_since_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['teaching_since' => 'not-a-number']);
    }

    /**
     *@test
     */
    public function teaching_since_must_be_a_reasonable_year()
    {
        $this->assertFieldIsInvalid(['teaching_since' => 1950]);
        $this->assertFieldIsInvalid(['teaching_since' => Carbon::today()->year + 1]);
    }

    /**
     *@test
     */
    public function the_chinese_ability_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['chinese_ability' => 'not-a-number']);
    }

    /**
     *@test
     */
    public function chinese_ability_must_be_between_1_and_4()
    {
        $this->assertFieldIsInvalid(['chinese_ability' => 0]);
        $this->assertFieldIsInvalid(['chinese_ability' => 5]);
    }

    private function assertFieldIsInvalid($field)
    {
        $valid_data = [
            'name' => 'Test Profile Name',
            'bio' => ['en' => 'Test english bio'],
            'nationality' => 'test_nationality',
            'qualifications' => 'test qualification',
            'teaching_since' => 1999,
            'chinese_ability' => 4,
            'teaching_specialties' => 'ESL',
        ];

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher->makeProfile();
        $profile_id = $teacher->fresh()->profile->id;

        $response = $this
            ->actingAs($teacher)
            ->postJson("/admin/profiles/{$profile_id}", array_merge($valid_data, $field));

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(array_keys($field)[0]);
    }
}