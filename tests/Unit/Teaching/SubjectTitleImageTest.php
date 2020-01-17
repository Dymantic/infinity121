<?php


namespace Tests\Unit\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class SubjectTitleImageTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_title_image()
    {
        Storage::fake('media');

        $subject = factory(Subject::class)->create();
        $this->assertCount(0, $subject->getMedia(Subject::TITLE_IMAGES));

        $image = $subject->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertCount(1, $subject->fresh()->getMedia(Subject::TITLE_IMAGES));

        Storage::disk('media')->assertExists($this->getImageDiskPath($image));
    }

    /**
     *@test
     */
    public function create_thumb_and_web_conversions()
    {
        Storage::fake('media');

        $subject = factory(Subject::class)->create();

        $image = $subject->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertTrue($image->fresh()->hasGeneratedConversion('thumb'));
        $this->assertTrue($image->fresh()->hasGeneratedConversion('web'));


        Storage::disk('media')
               ->assertExists($this->getImageConversionDiskPaths($image, ['thumb', 'web']));
    }

    /**
     *@test
     */
    public function get_title_image_urls()
    {
        Storage::fake('media');

        $has_image = factory(Subject::class)->create();
        $no_image = factory(Subject::class)->create();

        $image = $has_image->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $this->assertEquals($image->fresh()->getUrl('thumb'), $has_image->titleImage('thumb'));
        $this->assertEquals($image->fresh()->getUrl('web'), $has_image->titleImage('web'));
        $this->assertEquals($image->fresh()->getUrl(), $has_image->titleImage());

        $this->assertNull($no_image->titleImage('thumb'));
        $this->assertNull($no_image->titleImage('web'));
        $this->assertNull($no_image->titleImage());
    }

    /**
     *@test
     */
    public function setting_a_title_image_overwrites_any_previous_one()
    {
        Storage::fake('media');

        $subject = factory(Subject::class)->create();

        $first = $subject->setTitleImage(UploadedFile::fake()->image('testpic.png'));
        Storage::disk('media')->assertExists($this->getImageDiskPath($first));

        $second = $subject->setTitleImage(UploadedFile::fake()->image('testpic2.png'));
        Storage::disk('media')->assertExists($this->getImageDiskPath($second));
        Storage::disk('media')->assertMissing($this->getImageDiskPath($first));

        $this->assertCount(1, $subject->getMedia(Subject::TITLE_IMAGES));
    }

    private function getImageDiskPath($image)
    {
        return Str::replaceFirst("/media/", "", $image->getUrl());
    }

    private function getImageConversionDiskPaths($image, $conversions)
    {
        return collect($conversions)->map(function($conversion) use ($image) {
            return Str::replaceFirst("/media/", "", $image->getUrl($conversion));
        })->all();
    }


}