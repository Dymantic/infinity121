<?php


namespace Tests\Unit\Translatable;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetTranslationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function get_translated_values()
    {
        $subject = Subject::create([
            'title' => ['en' => 'test title', 'zh' => 'zh test title'],
            'description' => ['en' => 'test description', 'zh' => 'zh test description'],
            'writeup' => ['en' => 'test writeup', 'zh' => 'zh test writeup'],
        ]);

        $this->assertEquals('test title', $subject->translated('title', 'en'));
        $this->assertEquals('zh test description', $subject->translated('description', 'zh'));
        //nontranslatable attributes are returned as is
        $this->assertEquals('test-title', $subject->translated('slug', 'en'));

        //non-existing translations returned as null
        $this->assertNull($subject->translated('title', 'fr'));
    }
}