<?php


namespace Tests\Feature\Locations;


use App\Locations\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateRegionTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_region_for_a_country()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/countries/{$country->id}/regions", [
            'name' => 'test region',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('regions', [
            'country_id' => $country->id,
            'name' => 'test region',
        ]);
    }

    /**
     *@test
     */
    public function the_region_name_is_required()
    {
        $country = factory(Country::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/countries/{$country->id}/regions", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function the_region_name_must_be_unique_for_a_country()
    {
        $countryA = factory(Country::class)->create();
        $countryB = factory(Country::class)->create();

        $countryA->addRegion('test region');

        $response = $this->asAdmin()->postJson("/admin/api/countries/{$countryA->id}/regions", [
            'name' => 'test region',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');

        $response = $this->asAdmin()->postJson("/admin/api/countries/{$countryB->id}/regions", [
            'name' => 'test region',
        ]);
        $response->assertSuccessful();
    }
}
