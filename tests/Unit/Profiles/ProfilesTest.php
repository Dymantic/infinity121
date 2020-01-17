<?php


namespace Tests\Unit\Profiles;


use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function profiles_can_be_scoped_to_teachers_only()
    {
        factory(User::class, 5)->state('admin-only')->create();
        factory(User::class, 5)->state('teacher-only')->create();
        User::all()->map->makeProfile();

        $this->assertCount(10, Profile::all());

        $this->assertCount(5, Profile::teachers()->get());
    }

    /**
     *@test
     */
    public function profiles_can_be_scoped_to_active_only()
    {
        factory(User::class)->state('admin-only')->create()->makeProfile();
        factory(User::class)->state('teacher-only')->create()->makeProfile();
        $public = factory(User::class)->state('teacher-only')->create()->makeProfile();
        $public->publish();

        $active_teachers = Profile::teachers()->active()->get();

        $this->assertCount(1, $active_teachers);
        $this->assertTrue($active_teachers->first()->is($public));
    }

    /**
     *@test
     */
    public function profiles_have_a_slug_based_on_name()
    {
        $teacher = factory(User::class)->state('teacher-only')->create(['name' => 'test user name']);
        $profile = $teacher->makeProfile();

        $this->assertEquals('test-user-name', $profile->slug);
    }

    /**
     *@test
     */
    public function profiles_have_spoken_languages()
    {
        $profile = factory(Profile::class)->create(['spoken_languages' => ["en", "zh"]]);

        $this->assertEquals(["en", "zh"], $profile->spoken_languages);
    }

    /**
     *@test
     */
    public function profiles_are_not_public_by_default()
    {
        $teacher = factory(User::class)->state('teacher-only')->create(['name' => 'test user name']);
        $profile = $teacher->makeProfile();

        $this->assertFalse($profile->is_public);
    }

    /**
     *@test
     */
    public function profiles_can_be_published()
    {
        $profile = factory(Profile::class)->state('private')->create();

        $profile->publish();

        $this->assertTrue($profile->fresh()->is_public);
    }

    /**
     *@test
     */
    public function profiles_can_be_retracted()
    {
        $profile = factory(Profile::class)->state('public')->create();

        $profile->retract();

        $this->assertFalse($profile->fresh()->is_public);
    }
}
