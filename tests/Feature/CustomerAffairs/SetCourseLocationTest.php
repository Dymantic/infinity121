<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\Locations\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SetCourseLocationTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_the_location_info_for_a_course()
    {
        $this->withoutExceptionHandling();

        $course = factory(Course::class)->create();
        $area = factory(Area::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/courses/{$course->id}/location", [
            'area_id' => $area->id,
            'address' => 'test address',
            'map_link' => 'link.test',
            'location_notes' => 'test notes',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'area_id' => $area->id,
            'address' => 'test address',
            'map_link' => 'link.test',
            'location_notes' => 'test notes',
        ]);
    }

    /**
     *@test
     */
    public function the_area_id_must_exist_in_the_areas_table()
    {
        $this->assertFieldIsInvalid(['area_id' => 999]);
    }

    /**
     *@test
     */
    public function the_address_is_required()
    {
        $this->assertFieldIsInvalid(['address' => null]);
    }

    private function assertFieldIsInvalid($field)
    {
        $course = factory(Course::class)->create();
        $area = factory(Area::class)->create();

        $valid = [
            'area_id' => $area->id,
            'address' => 'test address',
            'map_link' => 'link.test',
            'location_notes' => 'test notes',
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/courses/{$course->id}/location", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
