<?php


namespace Tests\Feature\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadSubjectTitleImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function upload_title_image_for_subject()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $this->withoutExceptionHandling();

        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/subjects/{$subject->id}/image", [
            'image' => UploadedFile::fake()->image('testpic.jpg'),
        ]);
        $response->assertStatus(200);

        $this->assertCount(1, $subject->getMedia(Subject::TITLE_IMAGES));
        $title_image = $subject->fresh()->getFirstMedia(Subject::TITLE_IMAGES);
        $this->assertEquals($title_image->getUrl('thumb'), $response->decodeResponseJson('image_src'));
    }

    /**
     *@test
     */
    public function the_image_is_required()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/subjects/{$subject->id}/image", [
            'image' => null,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    /**
     *@test
     */
    public function the_image_must_be_a_valid_image_file()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/subjects/{$subject->id}/image", [
            'image' => UploadedFile::fake()->create('not-an-image.docx'),
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
