<?php


namespace Tests\Unit\Teaching;


use App\Locations\Area;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeacherWorkingAreasTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_working_areas_for_a_teacher()
    {
        $teacher = $this->createTeacher();

        $areaA = factory(Area::class)->create();
        $areaB = factory(Area::class)->create();

        $teacher->setWorkingAreas([$areaA->id, $areaB->id]);

        $this->assertCount(2, $teacher->fresh()->workingAreas);
        $this->assertTrue($teacher->fresh()->workingAreas->contains($areaA));
        $this->assertTrue($teacher->fresh()->workingAreas->contains($areaB));
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
        ]);
    }
}
