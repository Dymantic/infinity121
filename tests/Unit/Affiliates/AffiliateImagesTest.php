<?php


namespace Tests\Unit\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\AssertsMediaModels;
use Tests\TestCase;

class AffiliateImagesTest extends TestCase
{
    use RefreshDatabase, AssertsMediaModels;

    /**
     *@test
     */
    public function add_image_to_affiliate()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $affiliate = factory(Affiliate::class)->create();

        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertInstanceOf(Media::class, $image);

        Storage::disk('media')->assertExists($this->getImageDiskPath($image));
    }

    /**
     *@test
     */
    public function adding_image_overwrites_previous_one()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $affiliate = factory(Affiliate::class)->create();
        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic.png'));
        $this->assertCount(1, $affiliate->getMedia(Affiliate::LOGO));

        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic_2.png'));
        $this->assertCount(1, $affiliate->fresh()->getMedia(Affiliate::LOGO));
    }

    /**
     *@test
     */
    public function images_are_saved_into_logos_media_collection()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $affiliate = factory(Affiliate::class)->create();

        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertCount(1, $affiliate->getMedia(Affiliate::LOGO));

        $this->assertEquals($image->id, $affiliate->getFirstMedia(Affiliate::LOGO)->id);
    }

    /**
     *@test
     */
    public function a_thumb_image_is_converted()
    {
        Storage::fake('media', config('filesystems.disks.media'));

        $affiliate = factory(Affiliate::class)->create();

        $image = $affiliate->addImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertTrue($image->fresh()->hasGeneratedConversion('thumb'));

        Storage::disk('media')
               ->assertExists($this->getImageConversionDiskPaths($image, ['thumb']));
    }
}
