<?php


namespace Tests\Feature\Users;


use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
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
            'spoken_languages' => ['en', 'sp'],
        ];

        $response = $this
            ->actingAs($teacher)
            ->postJson("/admin/api/profiles/{$profile_id}", $profile_data);

        $response->assertStatus(200);

        $this->assertDatabaseHas('profiles', [
            'name' => 'Test Profile Name',
            'nationality' => 'test_nationality',
            'qualifications' => 'test qualification',
            'teaching_since' => 1999,
            'chinese_ability' => 4,
            'spoken_languages' => json_encode(['en', 'sp']),
        ]);

        $this->assertDatabaseHasWithTranslations('en', 'profiles', [
            'bio' => 'Test english bio',
        ]);

    }

    /**
     *@test
     */
    public function a_non_admin_cannot_update_another_teachers_profile()
    {
//        $this->withoutExceptionHandling();

        $non_admin = factory(User::class)->state('teacher-only')->create();
        $teacher = factory(Profile::class)->create();

        $before_request = $teacher->toArray();

        $response = $this->actingAs($non_admin)->postJson("/admin/api/profiles/{$teacher->id}", [
            'name' => 'Test Profile Name',
            'bio' => ['en' => 'Test english bio'],
            'nationality' => 'test_nationality',
            'qualifications' => 'test qualification',
            'teaching_since' => 1999,
            'chinese_ability' => 4,
            'spoken_languages' => ['en', 'sp'],
        ]);
        $response->assertStatus(Response::HTTP_FORBIDDEN);

        $this->assertEquals($before_request, $teacher->fresh()->toArray());
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

    /**
     *@test
     */
    public function spoken_languages_must_be_an_array()
    {
        $this->assertFieldIsInvalid(['spoken_languages' => 'en']);
    }

    /**
     *@test
     */
    public function all_spoken_language_codes_must_be_recognised()
    {
        $this->assertFieldIsInvalid(['spoken_languages' => ['en', 'qq']], "spoken_languages.1");
    }

    private function assertFieldIsInvalid($field, $expected_error_key = null)
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
            ->postJson("/admin/api/profiles/{$profile_id}", array_merge($valid_data, $field));

        $response->assertStatus(422);
        $error_key = $expected_error_key ?? array_keys($field)[0];
        $response->assertJsonValidationErrors($error_key);
    }
}
