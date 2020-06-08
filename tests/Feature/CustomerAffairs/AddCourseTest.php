<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Customer;
use App\Profile;
use App\Teaching\Subject;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AddCourseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function add_a_course_for_a_customer()
    {
        $this->withoutExceptionHandling();

        $customer = factory(Customer::class)->create();
        $subject = factory(Subject::class)->create();


        $response = $this->asAdmin()->postJson("/admin/api/customers/{$customer->id}/courses", [
            'subject_id'    => $subject->id,
            'total_lessons' => 20,
            'starts_from' => Carbon::today()->addWeek()->setDay(3)->format('Y-m-d'),
            'students'      => [
                ['name' => 'test student A', 'age' => 'test age'],
                ['name' => 'test student B', 'age' => 'test age'],
            ]
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('courses', [
            'customer_id'   => $customer->id,
            'subject_id'    => $subject->id,
            'total_lessons' => 20,
            'starts_from' => Carbon::today()->addWeek()->setDay(3),
            'students'      => json_encode([
                ['name' => 'test student A', 'age' => 'test age'],
                ['name' => 'test student B', 'age' => 'test age'],
            ])
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
    public function the_starts_from_date_is_required()
    {
        $this->assertFieldIsInvalid(['starts_from' => null]);
    }

    /**
     *@test
     */
    public function starts_from_must_be_a_date()
    {
        $this->assertFieldIsInvalid(['starts_from' => 'not-a-date']);
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
        $customer = factory(Customer::class)->create();
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
            ->postJson("/admin/api/customers/{$customer->id}/courses", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors($error_name ?? array_key_first($field));
    }
}
