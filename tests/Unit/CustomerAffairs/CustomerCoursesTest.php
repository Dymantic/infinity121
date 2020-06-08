<?php

namespace Tests\Unit\CustomerAffairs;

use App\CustomerAffairs\Course;
use App\CustomerAffairs\Customer;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CustomerCoursesTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_course_to_customer()
    {
        $customer = factory(Customer::class)->create();
        $subject = factory(Subject::class)->create();

        $info = [
            'subject_id' => $subject->id,
            'total_lessons' => 20,
            'students' => [
                ['name' => 'test student A', 'age' => 'test age'],
                ['name' => 'test student B', 'age' => 'test age'],
            ],
            'starts_from' => Carbon::today()->addWeek()->setDay(3)->format('Y-m-d')
        ];

        $course = $customer->addCourse($info);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertTrue($course->customer->is($customer));
        $this->assertTrue($course->subject->is($subject));
        $this->assertEquals(20, $course->total_lessons);
        $this->assertTrue($course->starts_from->isSameDay(Carbon::today()->addWeek()->setDay(3)));

        $this->assertCount(2, $course->students);
        $this->assertEquals(['name' => 'test student A', 'age' => 'test age'], $course->students[0]);
        $this->assertEquals(['name' => 'test student B', 'age' => 'test age'], $course->students[1]);
    }
}
