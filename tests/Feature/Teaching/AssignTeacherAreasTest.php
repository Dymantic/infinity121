<?php


namespace Tests\Feature\Teaching;


use App\Locations\Area;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AssignTeacherAreasTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function teacher_sets_working_areas()
    {
        $this->withoutExceptionHandling();

        $teacher = $this->createTeacher();

        $areaA = factory(Area::class)->create();
        $areaB = factory(Area::class)->create();

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/working-areas", [
            'area_ids' => [$areaA->id, $areaB->id],
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('area_profile', [
            'area_id' => $areaA->id,
            'profile_id' => $teacher->id,
        ]);

        $this->assertDatabaseHas('area_profile', [
            'area_id' => $areaB->id,
            'profile_id' => $teacher->id,
        ]);
    }

    /**
     *@test
     */
    public function the_area_ids_are_required()
    {
        $teacher = $this->createTeacher();

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/working-areas", []);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('area_ids');
    }

    /**
     *@test
     */
    public function the_area_ids_must_be_an_array()
    {
        $teacher = $this->createTeacher();

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/working-areas", [
            'area_ids' => 'not-an-array'
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors('area_ids');
    }

    /**
     *@test
     */
    public function the_area_ids_can_be_empty()
    {
        $teacher = $this->createTeacher();
        $area = factory(Area::class)->create();
        $teacher->setWorkingAreas([$area->id]);

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/working-areas", [
            'area_ids' => []
        ]);
        $response->assertSuccessful();

        $this->assertCount(0, $teacher->workingAreas);
    }

    /**
     *@test
     */
    public function each_area_id_must_exists_in_areas_table()
    {
        $teacher = $this->createTeacher();
        $area = factory(Area::class)->create();

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/working-areas", [
            'area_ids' => [$area->id, 999]
        ]);
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors('area_ids.1');

        $this->assertCount(0, $teacher->workingAreas);
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
        ]);
    }
}
