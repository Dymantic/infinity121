<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UploadAffiliateImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function upload_affiliate_image()
    {
        Storage::fake('media');
        $this->withoutExceptionHandling();

        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/affiliates/{$affiliate->id}/image", [
            "image" => UploadedFile::fake()->image('testpic.png'),
        ]);
        $response->assertStatus(200);

        $this->assertCount(1, $affiliate->getMedia(Affiliate::LOGO));

        $affiliate_image = $affiliate->fresh()->getFirstMedia(Affiliate::LOGO);
        $this->assertEquals($affiliate_image->getUrl('thumb'), $response->decodeResponseJson('image_src'));
    }

    /**
     *@test
     */
    public function the_image_is_required()
    {
        Storage::fake();

        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/affiliates/{$affiliate->id}/image", [
            "image" => '',
        ]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('image');
    }

    /**
     *@test
     */
    public function the_image_must_be_a_valid_image_file()
    {
        Storage::fake();

        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/affiliates/{$affiliate->id}/image", [
            "image" => UploadedFile::fake()->create('not-valid-image.docx'),
        ]);
        $response->assertStatus(422);

        $response->assertJsonValidationErrors('image');
    }
}
