<?php


namespace Tests\Unit\Profiles;


use App\Locations\Area;
use App\Profile;
use App\Teaching\Subject;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PresentProfilesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function presents_for_current_locale()
    {
        $profile = factory(Profile::class)->create(['bio' => ['en' => 'English Bio', 'zh' => 'Chinese Bio']]);

        app()->setLocale('en');
        $presentation = $profile->forCurrentLang();
        $this->assertEquals('English Bio', $presentation['bio']);

        app()->setLocale('zh');
        $presentation = $profile->forCurrentLang();
        $this->assertEquals('Chinese Bio', $presentation['bio']);
    }

    /**
     * @test
     */
    public function profiles_have_correct_info_in_array()
    {
        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher_profile = factory(Profile::class)->state('public')->create([
            'user_id'              => $teacher->id,
            'name'                 => 'test name',
            'bio'                  => ['en' => 'English Bio', 'zh' => 'Chinese Bio'],
            'nationality'          => 'zaf',
            'qualifications'       => 'test-qual',
            'teaching_specialties' => 'test-spec',
            'teaching_since'       => 2010,
            'chinese_ability'      => 2,
            'spoken_languages'     => ['en', 'zh'],
        ]);
        $teacher_profile->assignSubjects([$subjectA->id, $subjectB->id]);

        $areaA = factory(Area::class)->create();
        $areaB = factory(Area::class)->create(['region_id' => $areaA->region_id]);
        $teacher_profile->setWorkingAreas([$areaA->id, $areaB->id]);

        $expected = [
            'id'                    => $teacher_profile->id,
            'name'                  => 'test name',
            'slug'                  => 'test-name',
            'bio'                   => ['en' => 'English Bio', 'zh' => 'Chinese Bio'],
            'nationality'           => ['en' => 'South African', 'zh' => '南非的', 'jp' => '南アフリカ人'],
            'country_code'          => 'zaf',
            'qualifications'        => 'test-qual',
            'teaching_specialties'  => 'test-spec',
            'teaching_since'        => 2010,
            'years_experience'      => Carbon::now()->year - 2010,
            'chinese_ability'       => 2,
            'chinese_ability_full'  => 'some',
            'is_public'             => true,
            'avatar_original'       => Profile::DEFAULT_AVATAR,
            'avatar_thumb'          => Profile::DEFAULT_AVATAR,
            'subjects'              => collect([$subjectA, $subjectB])->map->toArray()->all(),
            'spoken_language_codes' => ['en', 'zh'],
            'spoken_languages'      => ["english", "chinese"],
            'position'              => null,
            'working_areas' => [
                [
                    'id' => $areaA->id,
                    'country_name' => $areaA->region->country->name,
                    'country_id' => $areaA->region->country->id,
                    'region_name' => $areaA->region->name,
                    'region_id' => $areaA->region_id,
                    'area_name' => $areaA->name,
                ],
                [
                    'id' => $areaB->id,
                    'country_name' => $areaB->region->country->name,
                    'country_id' => $areaB->region->country->id,
                    'region_name' => $areaB->region->name,
                    'region_id' => $areaB->region_id,
                    'area_name' => $areaB->name,
                ]
            ]
        ];

        $this->assertEquals($expected, $teacher_profile->toArray());

    }
}
