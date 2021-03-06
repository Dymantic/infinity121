<?php


namespace Tests\Unit\CustomerAffairs;


use App\Calendar\DateFormatter;
use App\CustomerAffairs\Course;
use App\CustomerAffairs\Customer;
use App\CustomerAffairs\Lesson;
use App\Locations\Area;
use App\Profile;
use App\Teaching\Subject;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class PresentCourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function present_as_data_array()
    {
        $customer = factory(Customer::class)->create([
            'name'  => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone',
        ]);

        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
        ]);
        $subject = factory(Subject::class)->create();
        $area = factory(Area::class)->create();

        $course = factory(Course::class)->create([
            'customer_id'    => $customer->id,
            'students'       => [
                ['name' => 'Student A', 'age' => 'adult'],
                ['name' => 'Student B', 'age' => 'college'],
            ],
            'starts_from'    => Carbon::tomorrow(),
            'subject_id'     => $subject->id,
            'area_id'        => $area->id,
            'map_link'       => 'link.to.map',
            'address'        => 'test address',
            'location_notes' => 'test notes',
        ]);
        $course->setLessonBlocks([
            ['day_of_week' => 2, 'starts' => '17:00', 'ends' => '19:00'],
            ['day_of_week' => 5, 'starts' => '18:00', 'ends' => '19:30'],
        ]);
        $course->assignTeacher($teacher->id);
        $should_start = Carbon::today()->addWeek()->startOfWeek();
        $course->confirm($should_start);
        $course->setNextLesson();

        $lesson = Lesson::where('course_id', $course->id)->first();


        $expected = [
            'id'                   => $course->id,
            'status'               => Course::STATUS_CONFIRMED,
            'is_complete'          => false,
            'customer_id'          => $customer->id,
            'customer'             => [
                'id'    => $customer->id,
                'name'  => 'test name',
                'email' => 'test@test.test',
                'phone' => 'test phone',
            ],
            'students'             => [
                ['name' => 'Student A', 'age' => 'adult'],
                ['name' => 'Student B', 'age' => 'college'],
            ],
            'total_lessons'        => $course->total_lessons,
            'starts_from'          => $should_start->format(DateFormatter::STANDARD),
            'starts_from_pretty'   => $should_start->format(DateFormatter::PRETTY),
            'subject_id'           => $subject->id,
            'subject_title'        => $subject->title,
            'lesson_blocks'        => [
                ['day_of_week' => 2, 'starts' => '17:00', 'ends' => '19:00'],
                ['day_of_week' => 5, 'starts' => '18:00', 'ends' => '19:30'],
            ],
            'area_id'              => $area->id,
            'area'                 => [
                'id'           => $area->id,
                'area_name'    => $area->name,
                'region_id'    => $area->region->id,
                'region_name'  => $area->region->name,
                'country_id'   => $area->region->country->id,
                'country_name' => $area->region->country->name,
            ],
            'map_link'             => 'link.to.map',
            'address'              => 'test address',
            'location_notes'       => 'test notes',
            'profile_id'           => $teacher->id,
            'teacher_name'         => $teacher->name,
            'teacher_bio'          => $teacher->bio,
            'teacher_avatar_thumb' => $teacher->getFirstMediaUrl(Profile::AVATAR, 'thumb'),
            'lessons'              => [$lesson->toArray()],
            'next_due_lesson'      => $lesson->toArray()
        ];

        $this->assertEquals($expected, $course->toArray());
    }
}
