<?php


namespace Tests\Feature\Locations;


use App\Locations\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateCountryTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_an_existing_country()
    {
        $this->withoutExceptionHandling();

        $country = factory(Country::class)->create(['name' => 'old name']);

        $response = $this->asAdmin()->postJson("/admin/api/countries/{$country->id}", [
            'name' => 'new name',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('countries', [
            'id' => $country->id,
            'name' => 'new name',
        ]);
    }

    /**
     *@test
     */
    public function the_country_must_be_unique()
    {
        $country = factory(Country::class)->create(['name' => 'old name']);
        factory(Country::class)->create(['name' => 'new name']);

        $response = $this->asAdmin()->postJson("/admin/api/countries/{$country->id}", [
            'name' => 'new name',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');

    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $country = factory(Country::class)->create(['name' => 'old name']);
        $response = $this->asAdmin()->postJson("/admin/api/countries/{$country->id}", [
            'name' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }
}
