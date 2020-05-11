<?php


namespace Tests\Feature\Teachers;


use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUpdatesTeacherProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function admin_update_another_teachers_profile()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher_profile = factory(Profile::class)->create(['user_id' => $teacher->id]);

        $response = $this->asAdmin()->postJson("/admin/api/profiles/{$teacher_profile->id}", [
            'name' => 'new test name',
            'nationality' => $teacher_profile->nationality,
            'spoken_languages' => ['en', 'zh'],
            'bio' => [
                'en' => 'test new en bio',
                'zh' => 'test new zh bio',
                'jp' => 'test new jp bio',
            ],
            'qualifications' => 'new test qualification',
            'teaching_since' => 1995,
            'chinese_ability' => 3
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('profiles', [
            'id' => $teacher_profile->id,
            'name' => 'new test name',
            'nationality' => $teacher_profile->nationality,
            'qualifications' => 'new test qualification',
            'teaching_since' => 1995,
            'chinese_ability' => 3,
            'spoken_languages' => json_encode(['en', 'zh']),
        ]);

        $this->assertDatabaseHasWithTranslations('en', 'profiles', [
            'bio' => 'test new en bio',
        ]);

        $this->assertDatabaseHasWithTranslations('zh', 'profiles', [
            'bio' => 'test new zh bio',
        ]);

        $this->assertDatabaseHasWithTranslations('jp', 'profiles', [
            'bio' => 'test new jp bio',
        ]);
    }
}
