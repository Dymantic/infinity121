<?php


namespace Tests\Feature\Teaching;


use App\Teaching\UnavailablePeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class UpdateUnavailableHoursTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_existing_unavailable_hours()
    {
        $this->withoutExceptionHandling();

        $period = factory(UnavailablePeriod::class)->create([
            'starts' => Carbon::tomorrow()->setHour(8),
            'ends' => Carbon::tomorrow()->addDays(3)->setHour(17),
        ]);

        $new_from = Carbon::today()->setHour(12);
        $new_to = Carbon::tomorrow()->addDays(4)->setHour(12);

        $response = $this->actingAs($period->teacher->user)
            ->postJson("/admin/api/me/unavailable-periods/{$period->id}", [
                'from' => $new_from->format('Y-m-d H:i:s'),
                'to' => $new_to->format('Y-m-d H:i:s'),
            ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('unavailable_periods', [
            'id' => $period->id,
            'profile_id' => $period->teacher->id,
            'starts' => $new_from,
            'ends' => $new_to,
        ]);
    }

    /**
     *@test
     */
    public function the_from_date_is_required()
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
        $period = factory(UnavailablePeriod::class)->create([
            'starts' => Carbon::tomorrow()->setHour(8),
            'ends' => Carbon::tomorrow()->addDays(3)->setHour(17),
        ]);

        $new_from = Carbon::today()->setHour(12);
        $new_to = Carbon::tomorrow()->addDays(4)->setHour(12);

        $valid = [
            'from' => $new_from->format('Y-m-d H:i:s'),
            'to' => $new_to->format('Y-m-d H:i:s'),
        ];

        $response = $this
            ->actingAs($period->teacher->user)
            ->postJson("/admin/api/me/unavailable-periods/{$period->id}", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
