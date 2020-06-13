<?php


namespace Tests\Unit\Times;


use App\Calendar\Exclusion;
use App\Calendar\Time;
use App\Calendar\TimePeriod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimePeriodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function check_if_period_contains_time()
    {
        $period = new TimePeriod("10:00", "12:00");

        $this->assertTrue($period->contains(new Time("10:00")));
        $this->assertTrue($period->contains(new Time("11:00")));
        $this->assertTrue($period->contains(new Time("10:01")));
        $this->assertTrue($period->contains(new Time("11:59")));
        $this->assertTrue($period->contains(new Time("12:00")));
        $this->assertFalse($period->contains(new Time("9:59")));
        $this->assertFalse($period->contains(new Time("12:10")));
    }

    /**
     * @test
     */
    public function check_if_period_overlaps_with_another()
    {
        $period = new TimePeriod("10:00", "12:00");

        $this->assertTrue(
            $period->overlaps(new TimePeriod("9:00", "11:00"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("10:40", "12:25"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("9:00", "10:00"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("12:00", "15:00"))
        );
        $this->assertTrue(
            $period->overlaps(new TimePeriod("9:00", "15:00"))
        );

        $this->assertFalse(
            $period->overlaps(new TimePeriod("9:00", "9:55"))
        );
        $this->assertFalse(
            $period->overlaps(new TimePeriod("12:01", "18:55"))
        );
    }

    /**
     * @test
     */
    public function can_merge_periods_together()
    {
        $cases = collect([
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("9:00", "11:00"),
                'expected' => new TimePeriod("9:00", "12:00"),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("11:00", "13:00"),
                'expected' => new TimePeriod("10:00", "13:00"),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("10:00", "15:30"),
                'expected' => new TimePeriod("10:00", "15:30"),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("9:25", "12:00"),
                'expected' => new TimePeriod("9:25", "12:00"),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("10:00", "12:00"),
                'expected' => new TimePeriod("10:00", "12:00"),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("9:00", "13:00"),
                'expected' => new TimePeriod("9:00", "13:00"),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("11:00", "11:30"),
                'expected' => new TimePeriod("10:00", "12:00"),
            ],
        ]);

        $cases->each(
            fn($case) => $this->assertPeriodsMatch(
                $case['periodA']->merge($case['periodB']), $case['expected']
            )
        );
    }

    /**
     * @test
     */
    public function can_check_for_equality()
    {
        $periodA = new TimePeriod("10:00", "12:00");
        $periodB = new TimePeriod("10:00", "12:00");
        $periodC = new TimePeriod("10:00", "12:05");
        $periodD = new TimePeriod("08:00", "12:00");
        $periodE = new TimePeriod("7:30", "15:00");

        $this->assertTrue($periodA->isSameAs($periodB));
        $this->assertFalse($periodA->isSameAs($periodC));
        $this->assertFalse($periodA->isSameAs($periodD));
        $this->assertFalse($periodA->isSameAs($periodE));
    }

    /**
     * @test
     */
    public function exclude_another_period()
    {
        $cases = collect([
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("9:00", "11:00"),
                'expected' => new Exclusion(null, new TimePeriod("11:00", "12:00")),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("11:00", "13:00"),
                'expected' => new Exclusion(new TimePeriod("10:00", "11:00"), null),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("10:00", "15:30"),
                'expected' => new Exclusion(null, null),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("9:25", "12:00"),
                'expected' => new Exclusion(null, null),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("10:00", "12:00"),
                'expected' => new Exclusion(null, null),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("9:00", "13:00"),
                'expected' => new Exclusion(null, null),
            ],
            [
                'periodA'  => new TimePeriod("10:00", "12:00"),
                'periodB'  => new TimePeriod("11:00", "11:30"),
                'expected' => new Exclusion(new TimePeriod("10:00", "11:00"), new TimePeriod("11:30", "12:00")),
            ],
        ]);

        $cases->each(
            fn($case) => $this->assertExclusionsMatch(
                $case['periodA']->exclude($case['periodB']), $case['expected'],
                sprintf("%s - %s and %s - %s", $case['periodA']->start->timeString, $case['periodA']->end->timeString,
                    $case['periodB']->start->timeString, $case['periodB']->end->timeString)
            )
        );
    }

    private function assertPeriodsMatch(TimePeriod $periodA, TimePeriod $periodB)
    {
        $matches = $periodA->startAsInt() === $periodB->startAsInt() &&
            $periodA->endAsInt() === $periodB->endAsInt();

        $this->assertTrue($matches, sprintf("[%s - %s] does not match expected [%s - %s]", $periodA->start->timeString,
            $periodA->end->timeString, $periodB->start->timeString, $periodB->end->timeString));
    }

    private function assertExclusionsMatch($exclusionA, $exclusionB, $mesage)
    {
        if ($exclusionA->before === null && $exclusionA->after === null) {
            $this->assertTrue($exclusionB->before === null && $exclusionB->after === null, $mesage);

            return;
        }

        if ($exclusionA->before === null) {
            $this->assertTrue(
                ($exclusionB->before === null) &&
                $exclusionA->after->isSameAs($exclusionB->after),
                $mesage
            );

            return;
        }

        if ($exclusionA->after === null) {
            $this->assertTrue(
                ($exclusionB->after === null) &&
                $exclusionA->before->isSameAs($exclusionB->before),
                $mesage
            );

            return;
        }

        $this->assertTrue($exclusionA->before->isSameAs($exclusionB->before), $mesage);
        $this->assertTrue($exclusionA->after->isSameAs($exclusionB->after), $mesage);


    }
}
