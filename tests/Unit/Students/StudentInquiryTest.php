<?php

namespace Tests\Unit\Students;

use App\Students\StudentInquiry;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentInquiryTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function presents_as_array_with_course()
    {
        $subject = factory(Subject::class)->create(['title' => ['en' => 'test course']]);
        $inquiry = StudentInquiry::create([
            'name' => 'test name',
            'phone' => 'test phone',
            'email' => 'test@test.test',
            'age' => 'test age',
            'english_ability' => 'none',
            'address' => '123 test street, test city',
            'subject_id' => $subject->id,
            'message' => 'test message',
        ]);

        $expected = [
            'id' => $inquiry->id,
            'name' => 'test name',
            'phone' => 'test phone',
            'email' => 'test@test.test',
            'age' => 'test age',
            'english_ability' => 'none',
            'address' => '123 test street, test city',
            'subject_id' => $subject->id,
            'course' => 'test course',
            'message' => 'test message',
        ];

        $this->assertEquals($expected, $inquiry->toArray());
    }
}
