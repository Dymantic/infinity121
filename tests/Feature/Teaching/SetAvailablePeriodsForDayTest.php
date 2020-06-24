<?php


namespace Tests\Feature\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SetAvailablePeriodsForDayTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_available_periods_for_day_of_week()
    {
        $this->withoutExceptionHandling();

        $teacher = $this->createTeacher();

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/available-periods", [
            'day_of_week' => Carbon::MONDAY,
            'periods' => [
                ["8:00", "12:00"], ["12:00", "14:00"]
            ]
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('available_periods', [
            'profile_id' => $teacher->id,
            'day_of_week' => Carbon::MONDAY,
            'starts' => 800,
            'ends' => 1400,
        ]);

//        $this->assertDatabaseHas('available_periods', [
//            'profile_id' => $teacher->id,
//            'day_of_week' => Carbon::MONDAY,
//            'starts' => 1400,
//            'ends' => 1900,
//        ]);
    }

    /**
     *@test
     */
    public function a_day_may_have_no_available_periods()
    {
        $this->withoutExceptionHandling();

        $teacher = $this->createTeacher();
        $periodA = new TimePeriod("09:00","12:00");
        $periodB = new TimePeriod("16:00","20:00");

        $teacher->setAvailabilityForDay(new Day(Carbon::MONDAY, [$periodA, $periodB]));

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/available-periods", [
            'day_of_week' => Carbon::MONDAY,
            'periods' => []
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseMissing('available_periods', [
            'profile_id' => $teacher->id,
            'day_of_week' => Carbon::MONDAY,
        ]);
    }

    /**
     *@test
     */
    public function the_day_of_the_week_is_required()
    {
        $this->assertFieldIsInvalid(['day_of_week' => null]);
    }

    /**
     *@test
     */
    public function the_day_of_week_must_be_an_integer()
    {
        $this->assertFieldIsInvalid(['day_of_week' => 'monday']);
    }

    /**
     *@test
     */
    public function the_day_of_week_must_be_between_0_and_6()
    {
        $invalid_days = collect([-1, 7, 10, 100]);

        $invalid_days->each(fn ($day) => $this->assertFieldIsInvalid(['day_of_week' => $day]));
    }

    /**
     *@test
     */
    public function the_periods_are_required()
    {
        $this->assertFieldIsInvalid(['periods' => null]);
    }

    /**
     *@test
     */
    public function the_periods_must_be_an_array_of_values()
    {
        $this->assertFieldIsInvalid(['periods' => 'not-an-array']);
    }

    /**
     *@test
     */
    public function each_period_must_be_a_valid_time_period()
    {
        $invalid_periods = collect([
            [
                'value' => 'not-a-tuple',
                'message' => 'valid period is a tuple of strings'
            ],

            [
                'value' => ["99", "12:00"],
                'message' => 'start not valid time string'
            ],
            [
                'value' => ["14:00", "14562:314"],
                'message' => 'end not valid time string'
            ],
            [
                'value' => ["14:00", "10:00"],
                'message' => 'start must be before end'
            ],
            [
                'value' => ["34:00", "10:00"],
                'message' => 'start has invalid hours'
            ],
            [
                'value' => ["4:70", "10:00"],
                'message' => 'start has invalid minutes'
            ],
        ]);

        $invalid_periods->each(
            fn ($period) => $this->assertFieldIsInvalid(['periods' => [$period['value']]], $period['message'], 'periods.0')
        );
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
    }

    private function assertFieldIsInvalid($field, $message = '', $error_field = null)
    {
        $valid = [
            'day_of_week' => Carbon::MONDAY,
            'periods' => [["09:00", "12:00"], ["14:00", "19:00"]],
        ];

        $teacher = $this->createTeacher();
        $response = $this->actingAs($teacher->user)
                         ->postJson("/admin/api/me/available-periods", array_merge($valid, $field));
        $this->assertEquals(422, $response->status(), $message);

        if(is_null($error_field)) {
            $error_field = array_key_first($field);
        }
        $response->assertJsonValidationErrors($error_field);

    }
}
