<?php

namespace Tests\Feature\Locations;

use App\Locations\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateCountryTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_new_country()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/countries", [
            'name' => 'test country',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('countries', ['name' => 'test country']);
    }

    /**
     *@test
     */
    public function the_country_must_be_unique()
    {
        factory(Country::class)->create(['name' => 'test country']);

        $response = $this->asAdmin()->postJson("/admin/api/countries", [
            'name' => 'test country',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');

    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $response = $this->asAdmin()->postJson("/admin/api/countries", [
            'name' => null,
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }
}
