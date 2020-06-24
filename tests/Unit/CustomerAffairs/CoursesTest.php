<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CoursesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_course_can_be_marked_as_complete()
    {
        $course = factory(Course::class)->state('ongoing')->create();
        $this->assertFalse($course->fresh()->isComplete());

        $course->complete();

        $this->assertTrue($course->fresh()->completed_on->isToday());
        $this->assertTrue($course->fresh()->isComplete());

        $this->assertEquals(Course::STATUS_COMPLETE, $course->status());
    }

    /**
     *@test
     */
    public function can_scope_to_active()
    {
        $ongoing = factory(Course::class)->state('confirmed')->create([
            'starts_from' => Carbon::yesterday(),
        ]);
        $confirmed = factory(Course::class)->state('confirmed')->create([
            'starts_from' => Carbon::yesterday(),
        ]);
        $unconfirmed = factory(Course::class)->state('unconfirmed')->create();
        $complete = factory(Course::class)->create()->complete();

        $scoped = Course::active()->get();

        $this->assertCount(2, $scoped);

        $this->assertTrue($scoped->contains($confirmed));
        $this->assertTrue($scoped->contains($ongoing));
    }
}
