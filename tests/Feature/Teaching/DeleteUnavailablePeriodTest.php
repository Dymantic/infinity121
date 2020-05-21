<?php


namespace Tests\Feature\Teaching;


use App\Teaching\UnavailablePeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteUnavailablePeriodTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_unavailable_period()
    {
        $this->withoutExceptionHandling();

        $period = factory(UnavailablePeriod::class)->create();

        $response = $this->actingAs($period->teacher->user)
            ->deleteJson("/admin/api/me/unavailable-periods/{$period->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('unavailable_periods', ['id' => $period->id]);
    }
}
