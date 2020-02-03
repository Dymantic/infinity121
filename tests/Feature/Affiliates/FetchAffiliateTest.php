<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_an_affiliate()
    {
        $this->withoutExceptionHandling();

        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->getJson("/admin/api/affiliates/{$affiliate->id}");
        $response->assertStatus(200);

        $this->assertEquals($affiliate->toArray(), $response->decodeResponseJson());
    }
}
