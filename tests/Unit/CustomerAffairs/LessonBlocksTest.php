<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\LessonBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class LessonBlocksTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function can_be_converted_to_next_date()
    {
        $block = factory(LessonBlock::class)->create([
            'day_of_week' => 5,
            'starts'      => '17:30',
            'ends'        => '19:00',
        ]);

        $nextLesson = $block->asNextDate();

        $this->assertTrue(
            Carbon::today()
//                  ->startOfWeek()
                  ->next('Friday')
                  ->setHour(17)
                  ->setMinutes(30)
                  ->eq($nextLesson)
        );
    }

    /**
     * @test
     */
    public function converts_to_correct_date_on_same_day()
    {
        $block = factory(LessonBlock::class)->create([
            'day_of_week' => Carbon::today()->weekDay(),
            'starts'      => Carbon::now()->hour + 2 . ':30',
            'ends'        => Carbon::now()->hour + 3 . ':30',
        ]);

        $nextLesson = $block->asNextDate();

        $this->assertTrue(
            Carbon::today()
                  ->setHour(Carbon::now()->hour + 2)
                  ->setMinutes(30)
                  ->eq($nextLesson)
        );
    }

    /**
     * @test
     */
    public function converts_to_next_week_if_time_passed()
    {
        $block = factory(LessonBlock::class)->create([
            'day_of_week' => Carbon::today()->weekDay(),
            'starts'      => Carbon::now()->hour - 2 . ':30',
            'ends'        => Carbon::now()->hour - 1 . ':30',
        ]);

        $nextLesson = $block->asNextDate();
//        dd($nextLesson->format('Y-m-d h:i'), Carbon::today()
//                                               ->addWeek()
//                                               ->setHour(Carbon::now()->hour - 2)
//                                               ->setMinutes(30)->format('Y-m-d h:i'));
        $this->assertTrue(
            Carbon::today()
                  ->addWeek()
                  ->setHour(Carbon::now()->hour - 2)
                  ->setMinutes(30)
                  ->eq($nextLesson)
        );
    }
}
