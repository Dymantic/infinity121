<?php


namespace Tests\Feature\Locations;


use App\Locations\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRegionTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_region()
    {
        $this->withoutExceptionHandling();

        $region = factory(Region::class)->create();
        $areaA = $region->addArea('one');
        $areaB = $region->addArea('two');

        $response = $this->asAdmin()->deleteJson("/admin/api/regions/{$region->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('regions', ['id' => $region->id]);
        $this->assertDatabaseMissing('areas', ['id' => $areaA->id]);
        $this->assertDatabaseMissing('areas', ['id' => $areaB->id]);
    }
}
