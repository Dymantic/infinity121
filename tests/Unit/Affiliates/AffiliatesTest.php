<?php

namespace Tests\Unit\Affiliates;

use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AffiliatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_new_fills_in_missing_translations_with_empty_text()
    {
        $affiliate = Affiliate::createNew([
            'name' => ['en' => 'test name', 'zh' => 'zh test name'],
            'description' => ['en' => 'test description', 'zh' => 'zh test description'],
            'link' => 'https://test.test',
        ]);

        $this->assertEquals("test name", $affiliate->name['en']);
        $this->assertEquals("zh test name", $affiliate->name['zh']);
        $this->assertEquals("", $affiliate->name['jp']);

        $this->assertEquals("test description", $affiliate->description['en']);
        $this->assertEquals("zh test description", $affiliate->description['zh']);
        $this->assertEquals("", $affiliate->description['jp']);

        $this->assertEquals("https://test.test", $affiliate->link);
    }

    /**
     *@test
     */
    public function affiliates_can_be_published()
    {
        $affiliate = factory(Affiliate::class)->state('private')->create();

        $affiliate->publish();

        $this->assertTrue($affiliate->is_public);
    }

    /**
     *@test
     */
    public function an_affiliate_can_be_retracted()
    {
        $affiliate = factory(Affiliate::class)->state('public')->create();

        $affiliate->retract();

        $this->assertFalse($affiliate->is_public);
    }

    /**
     *@test
     */
    public function can_be_scoped_to_public()
    {
        factory(Affiliate::class, 4)->state('public')->create();
        factory(Affiliate::class, 3)->state('private')->create();

        $scoped = Affiliate::public()->get();

        $this->assertCount(4, $scoped);

        $scoped->each(fn($affiliate) => $this->assertTrue($affiliate->is_public));
    }
}
