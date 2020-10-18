<?php


namespace Tests\Feature\Nationalities;


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FetchNationalitiesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_list_of_all_nationalities()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->getJson("/admin/api/nationalities");
        $response->assertStatus(200);

        $fetched_nationalities = $response->json();

        $this->assertArrayHasKey("zaf", $fetched_nationalities);
    }
}
