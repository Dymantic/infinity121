<?php


namespace Tests\Unit\Users;


use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeachersProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function creating_a_new_teacher_user_also_creates_a_profile()
    {
        $teacher = User::addTeacher([
            'name' => 'test name',
            'email' => 'test@test.test',
            'password' => 'password'
        ]);

        $profile = $teacher->fresh()->profile;

        $this->assertInstanceOf(Profile::class, $profile);
    }

    /**
     *@test
     */
    public function the_new_profile_has_a_default_empty_english_bio()
    {
        $teacher = User::addTeacher([
            'name' => 'test name',
            'email' => 'test@test.test',
            'password' => 'password'
        ]);

        $profile = $teacher->fresh()->profile;

        $this->assertEquals(["en" => ''], $profile->bio);
    }

    /**
     *@test
     */
    public function adding_an_admin_teacher_also_adds_profile()
    {
        $admin = User::addAdmin([
            'name' => 'test name',
            'email' => 'test@test.test',
            'password' => 'password',
            'is_teacher' => true,
        ]);

        $profile = $admin->fresh()->profile;

        $this->assertInstanceOf(Profile::class, $profile);
    }

    /**
     *@test
     */
    public function a_profile_has_correct_info_in_to_array_format()
    {
        Storage::fake('media');

        $profile = factory(Profile::class)->create([
            'name' => 'Test name',
            'bio' => ['en' => 'test en bio', 'zh' => 'test zh bio'],
            'nationality' => 'test nationality',
            'qualifications' => 'test qualification',
            'teaching_since' => 2010,
            'chinese_ability' => 1,
        ]);
        $avatar = $profile->setProfileImage(UploadedFile::fake()->image('testpic.png'));

        $expected = [
            'id' => $profile->id,
            'name' => 'Test name',
            'bio' => ['en' => 'test en bio', 'zh' => 'test zh bio'],
            'nationality' => 'test nationality',
            'qualifications' => 'test qualification',
            'teaching_since' => 2010,
            'chinese_ability' => 1,
            'avatar_original' => $avatar->getUrl(),
            'avatar_thumb' => $avatar->getUrl('thumb'),
        ];

        $this->assertEquals($expected, $profile->fresh()->toArray());
    }
}