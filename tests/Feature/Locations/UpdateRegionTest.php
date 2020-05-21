<?php


namespace Tests\Feature\Locations;


use App\Locations\Country;
use App\Locations\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateRegionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function update_an_existing_region()
    {
        $this->withoutExceptionHandling();

        $region = factory(Region::class)->create(['name' => 'old name']);

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$region->id}", [
            'name' => 'new name',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('regions', [
            'id'   => $region->id,
            'name' => 'new name',
        ]);
    }

    /**
     *@test
     */
    public function the_region_name_is_required()
    {
        $region = factory(Region::class)->create(['name' => 'old name']);

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$region->id}", [
            'name' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function the_region_name_must_be_unique_in_a_country()
    {
        $country = factory(Country::class)->create();
        $country->addRegion('existing region');

        $test_region = factory(Region::class)->create([
            'country_id' => $country->id,
            'name' => 'old region'
        ]);
        $control_region = factory(Region::class)->create([
            'name' => 'old region'
        ]);

        $this->assertNotEquals($control_region->country->id, $test_region->country->id);

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$test_region->id}", [
            'name' => 'existing region',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$control_region->id}", [
            'name' => 'existing region',
        ]);
        $response->assertSuccessful();
    }
}
