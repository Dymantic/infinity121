<?php


namespace Tests\Feature\Locations;


use App\Locations\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CreateAreaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function create_an_area_for_a_given_region()
    {
        $this->withoutExceptionHandling();

        $region = factory(Region::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$region->id}/areas", [
            'name' => 'test area',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('areas', [
            'region_id' => $region->id,
            'name'      => 'test area',
        ]);
    }

    /**
     *@test
     */
    public function the_area_name_is_required()
    {
        $region = factory(Region::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$region->id}/areas", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function the_name_should_be_unique_in_an_region()
    {
        $regionA = factory(Region::class)->create();
        $regionB = factory(Region::class)->create();

        $regionA->addArea('test area');

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$regionA->id}/areas", [
            'name' => 'test area',
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('name');

        $response = $this->asAdmin()->postJson("/admin/api/regions/{$regionB->id}/areas", [
            'name' => 'test area',
        ]);
        $response->assertSuccessful();

    }
}
