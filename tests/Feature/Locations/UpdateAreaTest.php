<?php


namespace Tests\Feature\Locations;


use App\Locations\Area;
use App\Locations\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateAreaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_an_existing_area()
    {
        $this->withoutExceptionHandling();

        $area = factory(Area::class)->create(['name' => 'old area']);

        $response = $this->asAdmin()->postJson("/admin/api/areas/{$area->id}", [
            'name' => 'new area',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('areas', [
            'id'   => $area->id,
            'name' => 'new area',
        ]);
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $area = factory(Area::class)->create(['name' => 'old area']);

        $response = $this->asAdmin()->postJson("/admin/api/areas/{$area->id}", [
            'name' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function area_name_must_be_unique_for_region()
    {
        $region = factory(Region::class)->create();
        $region->addArea('new name');

        $test_area = factory(Area::class)->create([
            'region_id' => $region->id,
            'name' => 'old name',
        ]);
        $control_area = factory(Area::class)->create(['name' => 'old name']);

        $this->assertNotEquals($control_area->region->id, $test_area->region->id);

        $response = $this->asAdmin()->postJson("/admin/api/areas/{$test_area->id}", [
            'name' => 'new name',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');

        $response = $this->asAdmin()->postJson("/admin/api/areas/{$control_area->id}", [
            'name' => 'new name',
        ]);
        $response->assertSuccessful();
    }
}
