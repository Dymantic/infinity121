<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\Locations\Area;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseLocationTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_course_location_data()
    {
        $course = factory(Course::class)->create();
        $area = factory(Area::class)->create();

        $course->setLocationData([
            'area_id' => $area->id,
            'address' => 'test address',
            'map_link' => 'link.test',
            'location_notes' => 'test notes'
        ]);

        $this->assertTrue($course->fresh()->area->is($area));
        $this->assertEquals('test address', $course->address);
        $this->assertEquals('link.test', $course->map_link);
        $this->assertEquals('test notes', $course->location_notes);
    }
}
