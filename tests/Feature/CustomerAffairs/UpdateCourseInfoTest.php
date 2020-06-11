<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\CustomerAffairs\Customer;
use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateCourseInfoTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_existing_course_basic_info()
    {
        $this->withoutExceptionHandling();

        $course = factory(Course::class)->create();
        $subject = factory(Subject::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/courses/{$course->id}", [
            'students' => [
                ['name' => 'new student name', 'age' => 'high school'],
            ],
            'total_lessons' => 6,
            'subject_id' => $subject->id,
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'students' => json_encode([
                ['name' => 'new student name', 'age' => 'high school'],
            ]),
            'total_lessons' => 6,
            'subject_id' => $subject->id,
        ]);
    }

    /**
     *@test
     */
    public function the_subject_id_is_required()
    {
        $this->assertFieldIsInvalid(['subject_id' => null]);
    }

    /**
     *@test
     */
    public function the_subject_id_must_exist_in_the_subjects_table()
    {
        $this->assertFieldIsInvalid(['subject_id' => 999]);
    }

    /**
     *@test
     */
    public function the_total_lessons_is_required()
    {
        $this->assertFieldIsInvalid(['total_lessons' => null]);
    }

    /**
     *@test
     */
    public function the_total_lessons_should_be_an_integer()
    {
        $this->assertFieldIsInvalid(['total_lessons' => 'not-an-integer']);
    }


    /**
     *@test
     */
    public function the_students_are_required()
    {
        $this->assertFieldIsInvalid(['students' => []]);
    }

    /**
     *@test
     */
    public function the_students_must_be_an_array()
    {
        $this->assertFieldIsInvalid(['students' => 'not-an-array']);
    }

    /**
     *@test
     */
    public function each_student_entry_must_be_an_array()
    {
        $this->assertFieldIsInvalid(['students' => ['only name']], 'students.0');
    }

    /**
     *@test
     */
    public function each_student_requires_a_name()
    {
        $this->assertFieldIsInvalid(['students' => [
            ['age' => 'adult', 'name' => null]
        ]], 'students.0.name');
    }

    /**
     *@test
     */
    public function each_student_requires_an_age()
    {
        $this->assertFieldIsInvalid(['students' => [
            ['age' => null, 'name' => 'test name']
        ]], 'students.0.age');
    }

    private function assertFieldIsInvalid($field, $error_name = null)
    {
        $course = factory(Course::class)->create();
        $subject = factory(Subject::class)->create();

        $valid = [
            'subject_id'    => $subject->id,
            'total_lessons' => 20,
            'starts_from' => Carbon::today()->addWeek()->setDay(3)->format('Y-m-d'),
            'students'      => [
                ['name' => 'test student A', 'age' => 'test age'],
                ['name' => 'test student B', 'age' => 'test age'],
            ]
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/courses/{$course->id}", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors($error_name ?? array_key_first($field));
    }
}
