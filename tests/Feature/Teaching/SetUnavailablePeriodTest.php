<?php


namespace Tests\Feature\Teaching;


use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SetUnavailablePeriodTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_a_period_of_unavailability()
    {
        $this->withoutExceptionHandling();

        $teacher = $this->createTeacher();

        $from = Carbon::tomorrow()->setHour(8)->setMinutes(0);
        $to = Carbon::tomorrow()
                    ->addDays(3)
                    ->setHour(12)
                    ->setMinutes(0);

        $response = $this->actingAs($teacher->user)->postJson("/admin/api/me/unavailable-periods", [
            'from' => $from->format('Y-m-d h:i'),
            'to' => $to->format('Y-m-d h:i'),
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('unavailable_periods', [
            'profile_id' => $teacher->id,
            'starts' => $from,
            'ends' => $to
        ]);
    }

    /**
     *@test
     */
    public function the_from_time_is_required()
    {
        $this->assertFieldIsInvalid(['from' => null]);
    }

    /**
     *@test
     */
    public function the_from_date_must_be_a_date_value()
    {
        $this->assertFieldIsInvalid(['from' => 'not-a-date']);
    }

    /**
     *@test
     */
    public function the_to_date_is_required()
    {
        $this->assertFieldIsInvalid(['to' => null]);
    }

    /**
     *@test
     */
    public function the_to_date_must_be_a_valid_date()
    {
        $this->assertFieldIsInvalid(['to' => 'not-a-date']);
    }

    /**
     *@test
     */
    public function the_to_date_mst_be_after_the_from_date()
    {
        $this->assertFieldIsInvalid(['to' => Carbon::yesterday()->setHour(8)->format('Y-m-d H:i:s')]);
    }

    private function assertFieldIsInvalid($field)
    {
        $teacher = $this->createTeacher();

        $from = Carbon::tomorrow()->setHour(8)->setMinutes(0);
        $to = Carbon::tomorrow()
                    ->addDays(3)
                    ->setHour(12)
                    ->setMinutes(0);

        $valid = [
            'from' => $from->format('Y-m-d h:i'),
            'to' => $to->format('Y-m-d h:i'),
        ];

        $response = $this
            ->actingAs($teacher->user)
            ->postJson("/admin/api/me/unavailable-periods", array_merge($valid, $field));

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(array_key_first($field));
    }

    private function createTeacher()
    {
        return factory(Profile::class)->create([
            'user_id' => factory(User::class)->state('teacher-only')
        ]);
    }
}
