<?php


namespace Tests\Feature\Teaching;


use App\Calendar\Day;
use App\Calendar\TimePeriod;
use App\Profile;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class FetchTeachersAvailablePeriodsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function fetch_existing_available_periods_for_signed_in_teacher()
    {
        $this->withoutExceptionHandling();

        $teacher = factory(Profile::class)
            ->create(['user_id' => factory(User::class)->state('teacher-only')]);

        $teacher->setAvailabilityForDay(new Day(Carbon::MONDAY, [
            new TimePeriod("10:00", "12:00"),
            new TimePeriod("14:00", "17:00")
        ]));

        $teacher->setAvailabilityForDay(new Day(Carbon::WEDNESDAY, [
            new TimePeriod("14:00", "19:30")
        ]));

        $teacher->setAvailabilityForDay(new Day(Carbon::FRIDAY, [
            new TimePeriod("09:00", "11:30"),
            new TimePeriod("16:00", "20:00")
        ]));

        $expected = [
            Carbon::MONDAY => [
                "periods" => [
                    ["starts" => "10:00", "ends" => "12:00"],
                    ["starts" => "14:00", "ends" => "17:00"],
                ],
            ],
            Carbon::TUESDAY => ['periods' => []],
            Carbon::WEDNESDAY => [
                "periods" => [
                    ["starts" => "14:00", "ends" => "19:30"],
                ],
            ],
            Carbon::THURSDAY => ['periods' => []],
            Carbon::FRIDAY => [
                "periods" => [
                    ["starts" => "9:00", "ends" => "11:30"],
                    ["starts" => "16:00", "ends" => "20:00"],
                ],
            ],
            Carbon::SATURDAY => ['periods' => []],
            Carbon::SUNDAY => ['periods' => []],
        ];

        $response = $this->actingAs($teacher->user)->getJson("/admin/api/me/available-periods");
        $response->assertSuccessful();

        $this->assertEquals($expected, $response->json());
    }
}
