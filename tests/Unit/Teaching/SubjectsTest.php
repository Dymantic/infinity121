<?php

namespace Tests\Unit\Teaching;

use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubjectsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_new_subject_from_english_title()
    {
        $subject = Subject::createNew('test subject');

        $this->assertInstanceOf(Subject::class, $subject);
        $this->assertEquals(['en' => 'test subject'], $subject->title);
        $this->assertEquals(['en' => ''], $subject->description);
        $this->assertEquals(['en' => ''], $subject->writeup);

        $this->assertEquals('test-subject', $subject->slug);
    }

    /**
     *@test
     */
    public function can_be_published()
    {
        $subject = factory(Subject::class)->state('private')->create();

        $subject->publish();

        $this->assertTrue($subject->fresh()->is_public);
    }

    /**
     *@test
     */
    public function can_be_retracted()
    {
        $subject = factory(Subject::class)->state('public')->create();

        $subject->retract();

        $this->assertFalse($subject->fresh()->is_public);
    }

    /**
     *@test
     */
    public function can_be_scoped_to_public()
    {
        factory(Subject::class, 3)->state('public')->create();
        factory(Subject::class, 2)->state('private')->create();

        $fetched = Subject::public()->get();

        $this->assertCount(3, $fetched);

        $fetched->each(fn ($subject) => $this->assertTrue($subject->is_public));
    }

    /**
     * @test
     */
    public function to_array_has_correct_attributes()
    {
        Storage::fake('media');

        $subject = factory(Subject::class)->state('public')->create([
            'title'       => ['en' => 'test title', 'zh' => 'zh test title'],
            'description' => ['en' => 'test description', 'zh' => 'zh test description'],
            'writeup'     => ['en' => 'test writeup', 'zh' => 'zh test writeup'],
        ]);

        $image = $subject->setTitleImage(UploadedFile::fake()->image('testpic.png'));

        $expected = [
            'id'          => $subject->id,
            'title'       => ['en' => 'test title', 'zh' => 'zh test title'],
            'description' => ['en' => 'test description', 'zh' => 'zh test description'],
            'writeup'     => ['en' => 'test writeup', 'zh' => 'zh test writeup'],
            'slug'        => 'test-title',
            'is_public' => true,
            'title_image' => [
                'thumb'    => $image->getUrl('thumb'),
                'web'      => $image->getUrl('web'),
                'original' => $image->getUrl(),
            ]
        ];

        $this->assertEquals($expected, $subject->fresh()->toArray());
    }
}
