<?php


namespace Tests\Feature\Locations;


use App\Locations\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteAreaTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_a_given_area()
    {
        $this->withoutExceptionHandling();

        $area = factory(Area::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/areas/{$area->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('areas', ['id' => $area->id]);
    }
}
