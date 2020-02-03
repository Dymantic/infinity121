<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublishAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function publish_an_affiliate()
    {
        $this->withoutExceptionHandling();

        $affiliate = factory(Affiliate::class)->create();
        $response = $this->asAdmin()->postJson("/admin/api/published-affiliates", [
            'affiliate_id' => $affiliate->id,
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHas('affiliates', [
            'id' => $affiliate->id,
            'is_public' => true,
        ]);
    }
}
