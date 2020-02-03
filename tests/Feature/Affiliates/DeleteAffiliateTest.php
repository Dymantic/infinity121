<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_affiliate()
    {
        $this->withoutExceptionHandling();

        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/affiliates/{$affiliate->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing("affiliates", ['id' => $affiliate->id]);
    }

    /**
     *@test
     */
    public function an_affiliate_can_only_be_deleted_by_admin()
    {
        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asTeacher()->deleteJson("/admin/api/affiliates/{$affiliate->id}");
        $response->assertStatus(403);

        $this->assertDatabaseHas("affiliates", ['id' => $affiliate->id]);
    }
}
