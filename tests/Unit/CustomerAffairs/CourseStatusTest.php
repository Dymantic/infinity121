<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\CustomerAffairs\Customer;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CourseStatusTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function new_course_is_unconfirmed()
    {
        $subject = factory(Subject::class)->create();
        $customer = factory(Customer::class)->create();
        $course = $customer->addCourse([
            'students' => [['name' => 'test name', 'age' => 'adult']],
            'total_lessons' => 20,
            'subject_id' => $subject->id,
        ]);

        $this->assertEquals(Course::STATUS_UNCONFIRMED, $course->status());
    }

    /**
     *@test
     */
    public function a_confirmed_course_with_future_start_date_has_a_confirmed_status()
    {
        $course = factory(Course::class)->state('confirmed')->create([
            'starts_from' => Carbon::tomorrow(),
        ]);

        $this->assertEquals(Course::STATUS_CONFIRMED, $course->status());
    }

    /**
     *@test
     */
    public function confirmed_course_with_past_start_date_is_ongoing()
    {
        $course = factory(Course::class)->state('confirmed')->create([
            'starts_from' => Carbon::yesterday(),
        ]);

        $this->assertEquals(Course::STATUS_ONGOING, $course->status());
    }
}
