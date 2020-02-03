<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchAffiliatesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_all_affiliates()
    {
        $this->withoutExceptionHandling();

        $affiliates = factory(Affiliate::class, 10)->create();

        $response = $this->asAdmin()->getJson("/admin/api/affiliates");
        $response->assertStatus(200);

        $fetched_affiliates = $response->decodeResponseJson();

        $this->assertCount(10, $fetched_affiliates);

        $this->assertEquals($affiliates->map->toArray()->all(), $fetched_affiliates);
    }
}
