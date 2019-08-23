<?php

namespace Tests\Feature\Profiles;

use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadProfileImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function upload_profile_image()
    {
        Storage::fake('media');

        $this->withoutExceptionHandling();

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher->makeProfile();

        $response = $this->actingAs($teacher)->postJson("/admin/me/profile/image", [
            'image' => UploadedFile::fake()->image('test-image.png')
        ]);
        $response->assertStatus(200);

        $profile = $teacher->fresh()->profile;

        $this->assertCount(1, $profile->getMedia(Profile::AVATAR));

        $this->assertEquals($profile->getFirstMedia(Profile::AVATAR)->getUrl('thumb'), $response->decodeResponseJson('image_src'));
    }

    /**
     *@test
     */
    public function the_image_must_be_a_valid_format()
    {
        Storage::fake('media');

        $teacher = factory(User::class)->state('teacher-only')->create();
        $teacher->makeProfile();

        $response = $this->actingAs($teacher)->postJson("/admin/me/profile/image", [
            'image' => UploadedFile::fake()->create('not-image.txt')
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}