<?php


namespace Tests\Feature\Teaching;


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

        $teacher->setAvailabilityFor(Carbon::MONDAY, [
            new TimePeriod("1000", "1200"),
            new TimePeriod("1400", "1700")
        ]);

        $teacher->setAvailabilityFor(Carbon::WEDNESDAY, [
            new TimePeriod("1400", "1930")
        ]);

        $teacher->setAvailabilityFor(Carbon::FRIDAY, [
            new TimePeriod("0900", "1130"),
            new TimePeriod("1600", "2000")
        ]);

        $expected = [
            [
                "day" => 1,
                "periods" => [
                    ["starts" => 1000, "ends" => 1200],
                    ["starts" => 1400, "ends" => 1700],
                ],
            ],
            [
                "day" => 3,
                "periods" => [
                    ["starts" => 1400, "ends" => 1930],
                ],
            ],
            [
                "day" => 5,
                "periods" => [
                    ["starts" => 900, "ends" => 1130],
                    ["starts" => 1600, "ends" => 2000],
                ],
            ],
        ];

        $response = $this->actingAs($teacher->user)->getJson("/admin/api/me/available-periods");
        $response->assertSuccessful();

        $this->assertEquals($expected, $response->decodeResponseJson());
    }
}
