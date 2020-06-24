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
        ];

        $course = $customer->addCourse($info);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertTrue($course->customer->is($customer));
        $this->assertTrue($course->subject->is($subject));
        $this->assertEquals(20, $course->total_lessons);

        $this->assertCount(2, $course->students);
        $this->assertEquals(['name' => 'test student A', 'age' => 'test age'], $course->students[0]);
        $this->assertEquals(['name' => 'test student B', 'age' => 'test age'], $course->students[1]);
    }

    /**
     *@test
     */
    public function can_confirm_a_course()
    {
        $course = factory(Course::class)->state('unconfirmed')->create();

        $course->confirm(Carbon::tomorrow());
        $course = $course->fresh();

        $this->assertTrue($course->isConfirmed());
        $this->assertTrue($course->starts_from->isSameDay(Carbon::tomorrow()));
        $this->assertTrue($course->confirmed_on->isSameDay(Carbon::today()));
    }

    /**
     *@test
     */
    public function can_be_scoped_to_confirmed()
    {
        $confirmed = factory(Course::class)->state('confirmed')->create();
        $unconfirmed = factory(Course::class)->state('unconfirmed')->create();

        $scoped = Course::confirmed()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($confirmed));
    }

    /**
     *@test
     */
    public function can_be_scoped_to_unconfirmed()
    {
        $confirmed = factory(Course::class)->state('confirmed')->create();
        $unconfirmed = factory(Course::class)->state('unconfirmed')->create();

        $scoped = Course::unconfirmed()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($unconfirmed));
    }
}
