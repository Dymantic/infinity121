<?php


namespace Tests\Unit\Nationalities;


use App\Nationalities;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NationalitiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function contains_a_list_of_nationalities()
    {
        $this->assertContains(['en' => 'South African', 'zh' => '南非的', 'jp' => '南アフリカ人'], Nationalities::all());
    }

    /**
     *@test
     */
    public function can_present_as_requested_language_only()
    {
        $for_en = Nationalities::forLang("en");

        $this->assertEquals($for_en["zaf"], "South African");
    }

    /**
     *@test
     */
    public function can_be_fetched_from_country_code()
    {
        $this->assertEquals(['en' => 'South African', 'zh' => '南非的', 'jp' => '南アフリカ人'], Nationalities::byCode("zaf"));
    }
}
