<?php

namespace Tests\Unit\Profiles;

use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_profile_image()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $profile = $this->makeProfile();

        $profile->setProfileImage(UploadedFile::fake()->image('testpic.jpg'));

        $this->assertCount(1, $profile->getMedia(Profile::AVATAR));

        Storage::disk('media')->assertExists($this->getAvatarDiskPath($profile));
    }

    /**
     *@test
     */
    public function it_makes_thumb_conversions()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $profile = $this->makeProfile();

        $profile->setProfileImage(UploadedFile::fake()->image('testpic.jpg'));
        $avatar = $profile->fresh()->getFirstMedia(Profile::AVATAR);

        $this->assertTrue($avatar->hasGeneratedConversion('thumb'));
        Storage::disk('media')
               ->assertExists($this->getAvatarConversionDiskPaths(['thumb'], $profile));

    }

    /**
     *@test
     */
    public function uploading_a_second_image_overwrites_the_first()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $profile = $this->makeProfile();

        $first = $profile->setProfileImage(UploadedFile::fake()->image('testpic.jpg'));
        $this->assertCount(1, $profile->getMedia(Profile::AVATAR));

        $second = $profile->setProfileImage(UploadedFile::fake()->image('test_two.jpg'));
        $this->assertCount(1, $profile->fresh()->getMedia(Profile::AVATAR));

        $this->assertTrue(Str::contains($second->getPath(), 'test_two'));
    }

    private function makeProfile()
    {
        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher->makeProfile();

        return $teacher->fresh()->profile;
    }

    private function getAvatarDiskPath($profile)
    {
        return Str::replaceFirst('/media/', '', $profile->fresh()->getFirstMedia(Profile::AVATAR)->getUrl());
    }

    private function getAvatarConversionDiskPaths($conversions, $profile)
    {
        return collect($conversions)->map(function($conversion) use ($profile) {
            return Str::replaceFirst('/media/', '', $profile->fresh()->getFirstMedia(Profile::AVATAR)->getUrl($conversion));
        })->all();
    }
}
