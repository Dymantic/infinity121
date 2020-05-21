<?php


namespace Tests\Feature\Locations;


use App\Locations\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCountryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function delete_an_existing_country()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create();
        $region = $country->addRegion('test region');
        $area = $region->addArea('test area');

        $response = $this->asAdmin()->deleteJson("/admin/api/countries/{$country->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('countries', ['id' => $country->id]);
        $this->assertDatabaseMissing('regions', ['id' => $region->id]);
        $this->assertDatabaseMissing('areas', ['id' => $area->id]);
    }
}
