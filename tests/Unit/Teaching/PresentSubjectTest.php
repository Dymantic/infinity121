<?php


namespace Tests\Unit\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PresentSubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function presents_with_the_current_locale()
    {
        $subject = factory(Subject::class)->create([
            'title'       => ['en' => 'english title', 'zh' => 'chinese title'],
            'description' => ['en' => 'english description', 'zh' => 'chinese description'],
            'writeup'     => ['en' => 'english writeup', 'zh' => 'chinese writeup'],
        ]);

        app()->setLocale('en');
        $presentation = $subject->forCurrentLang();
        $this->assertEquals('english title', $presentation['title']);
        $this->assertEquals('english description', $presentation['description']);
        $this->assertEquals('english writeup', $presentation['writeup']);

        app()->setLocale('zh');
        $presentation = $subject->forCurrentLang();
        $this->assertEquals('chinese title', $presentation['title']);
        $this->assertEquals('chinese description', $presentation['description']);
        $this->assertEquals('chinese writeup', $presentation['writeup']);
    }
}
