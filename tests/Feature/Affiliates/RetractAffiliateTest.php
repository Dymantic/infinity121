<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetractAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function retract_a_published_affiliate()
    {
        $this->withoutExceptionHandling();

        $affiliate = factory(Affiliate::class)->state('public')->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/published-affiliates/{$affiliate->id}");
        $response->assertStatus(200);

        $this->assertDatabaseHas('affiliates', [
            'id' => $affiliate->id,
            'is_public' => false,
        ]);
    }
}
