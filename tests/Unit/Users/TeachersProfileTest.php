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
     * @test
     */
    public function creating_a_new_teacher_user_also_creates_a_profile()
    {
        $teacher = User::addTeacher([
            'name'     => 'test name',
            'email'    => 'test@test.test',
            'password' => 'password'
        ]);

        $profile = $teacher->fresh()->profile;

        $this->assertInstanceOf(Profile::class, $profile);
    }

    /**
     * @test
     */
    public function the_new_profile_has_a_default_empty_english_bio()
    {
        $teacher = User::addTeacher([
            'name'     => 'test name',
            'email'    => 'test@test.test',
            'password' => 'password'
        ]);

        $profile = $teacher->fresh()->profile;

        $this->assertEquals(["en" => ''], $profile->bio);
    }

    /**
     * @test
     */
    public function adding_an_admin_teacher_also_adds_profile()
    {
        $admin = User::addAdmin([
            'name'       => 'test name',
            'email'      => 'test@test.test',
            'password'   => 'password',
            'is_teacher' => true,
        ]);

        $profile = $admin->fresh()->profile;

        $this->assertInstanceOf(Profile::class, $profile);
    }


}
