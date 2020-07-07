<?php


namespace Tests\Unit\CustomerAffairs;


use App\Calendar\DateFormatter;
use App\CustomerAffairs\Course;
use App\CustomerAffairs\Lesson;
use App\CustomerAffairs\LessonLog;
use App\Locations\Area;
use App\Profile;
use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LessonsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_scope_upcoming_to_profile()
    {
        $courseA = factory(Course::class)->create();
        $courseB = factory(Course::class)->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $courseA->setLessonBlocks([
            [
                'day_of_week' => 3,
                'starts'      => '10:00',
                'ends'        => '11:00',
            ]
        ]);

        $courseB->setLessonBlocks([
            [
                'day_of_week' => 2,
                'starts'      => '10:00',
                'ends'        => '11:00',
            ]
        ]);
        $courseA->assignTeacher($teacher->id);
        $courseB->assignTeacher($teacher->id);

        $courseA->confirm(Carbon::today());
        $courseB->confirm(Carbon::today());
        $due_lesson = $courseA->setNextLesson();
        $courseB->setNextLesson();
        $courseB->confirmed_on = null;
        $courseB->save();

        $complete = factory(Lesson::class)
            ->state('completed')->create(['profile_id' => $teacher->id]);

        $other_teacher = factory(Lesson::class)->state('due')->create();

        $this->assertCount(4, Lesson::all());

        $scoped = Lesson::dueByTeacher($teacher)->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($due_lesson));
    }

    /**
     * @test
     */
    public function can_be_scoped_to_completed_by_teacher()
    {
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
        $course = factory(Course::class)->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => 3,
                'starts'      => '10:00',
                'ends'        => '11:00',
            ]
        ]);
        $course->assignTeacher($teacher->id);
        $course->confirm(Carbon::today());
        $course->setNextLesson();

        $by_teacher = factory(Lesson::class)
            ->state('completed')->create(['profile_id' => $teacher->id]);
        factory(Lesson::class)->state('completed')->create();

        $scoped = Lesson::completedByTeacher($teacher)->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($by_teacher));
    }

    /**
     * @test
     */
    public function can_scope_to_due_lessons()
    {
        $due = factory(Lesson::class)->state('due')->create();
        $complete = factory(Lesson::class)->state('completed')->create();

        $scoped = Lesson::due()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($due));
    }

    /**
     * @test
     */
    public function can_scope_to_completed()
    {
        $due = factory(Lesson::class)->state('due')->create();
        $complete = factory(Lesson::class)->state('completed')->create();

        $scoped = Lesson::completed()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($complete));
    }

    /**
     * @test
     */
    public function can_log_a_lesson()
    {
        $lesson = factory(Lesson::class)->state('due')->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);

        $lesson->log($teacher, $this->validLessonLog());
        $lesson->refresh();

        $this->assertEquals($teacher->id, $lesson->profile_id);
        $this->assertEquals('done', $lesson->status);
        $this->assertTrue($lesson->complete);
        $this->assertEquals(Carbon::today(), $lesson->completed_on);
        $this->assertEquals("11:00", $lesson->actual_start);
        $this->assertEquals("12:00", $lesson->actual_end);
        $this->assertEquals("test material", $lesson->material_taught);
        $this->assertEquals("test log", $lesson->teacher_log);
        $this->assertEquals("poor", $lesson->student_interaction);
        $this->assertEquals("okay", $lesson->student_comprehension);
        $this->assertEquals("good", $lesson->student_confidence);
        $this->assertEquals("excellent", $lesson->student_output);

    }

    /**
     * @test
     */
    public function logging_a_lesson_sets_the_next_lesson()
    {
        $course = factory(Course::class)->create();
        $course->setLessonBlocks([
            [
                'day_of_week' => 1,
                'starts'      => "10:00",
                'ends'        => "11:00",
            ]
        ]);
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only'),
        ]);

        $course->assignTeacher($teacher->id);
        $course->confirm(Carbon::today());
        $first_lesson = $course->setNextLesson();

        $first_lesson->log($teacher, $this->validLessonLog());

        $this->assertCount(2, $course->lessons);
        $new_lesson = $course->lessons->first(fn($l) => $l->id !== $first_lesson->id);

        $this->assertNull($new_lesson->profile_id);
        $this->assertFalse($new_lesson->complete);
    }

    /**
     * @test
     */
    public function can_scoped_to_logged_lessons()
    {
        $due = factory(Lesson::class)->state('due')->create();
        $completed = factory(Lesson::class)->state('completed')->create();
        $cancelled = factory(Lesson::class)->state('cancelled')->create();

        $scoped = Lesson::logged()->get();

        $this->assertCount(2, $scoped);

        $this->assertTrue($scoped->contains($completed));
        $this->assertTrue($scoped->contains($cancelled));
    }

    /**
     * @test
     */
    public function can_be_scoped_to_require_logging()
    {
        $requires = factory(Lesson::class)->state('due')->create([
            'lesson_date' => Carbon::yesterday(),
        ]);
        $future = factory(Lesson::class)->state('due')->create([
            'lesson_date' => Carbon::tomorrow(),
        ]);
        $logged = factory(Lesson::class)->state('completed')->create();

        $scoped = Lesson::requiresLogging()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->contains($requires));
    }

    /**
     *@test
     */
    public function can_cancel_a_lesson()
    {
        $lesson = factory(Lesson::class)->state('due')->create();
        $teacher = factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);

        $original_date = $lesson->lesson_date;
        $original_start = $lesson->starts;
        $original_end = $lesson->ends;

        $lesson->cancel($teacher, 'test reason');

        $lesson->refresh();

        $this->assertTrue($lesson->complete);
        $this->assertEquals(Lesson::STATUS_CANCELLED, $lesson->status);
        $this->assertEquals('test reason', $lesson->teacher_log);
        $this->assertNull($lesson->material_taught);
        $this->assertNull($lesson->student_interaction);
        $this->assertNull($lesson->student_comprehension);
        $this->assertNull($lesson->student_confidence);
        $this->assertNull($lesson->student_output);
        $this->assertTrue($original_date->eq($lesson->completed_on));
        $this->assertEquals($original_start, $lesson->actual_start);
        $this->assertEquals($original_end, $lesson->actual_end);
    }

    private function validLessonLog(): LessonLog
    {
        return new LessonLog([
            'completed_on'          => Carbon::today()->format(DateFormatter::STANDARD),
            'actual_start'          => '11:00',
            'actual_end'            => '12:00',
            'material_taught'       => 'test material',
            'teacher_log'           => 'test log',
            'student_interaction'   => 'poor',
            'student_comprehension' => 'okay',
            'student_confidence'    => 'good',
            'student_output'        => 'excellent',
        ]);
    }
}
