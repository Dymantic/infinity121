<?php


namespace Tests\Unit\CustomerAffairs;


use App\CustomerAffairs\Course;
use App\CustomerAffairs\LessonBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseLessonBlocksTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_lesson_blocks_on_a_course()
    {
        $course = factory(Course::class)->create();

        $blocks = $course->setLessonBlocks([
            [
                'day_of_week' => 3,
                'starts'      => '16:00',
                'ends'        => '17:00',
            ],
            [
                'day_of_week' => 5,
                'starts'      => '18:00',
                'ends'        => '20:00',
            ]
        ]);

        $this->assertCount(2, $blocks);

        $blocks->each(function($block, $index) {
           $this->assertInstanceOf(LessonBlock::class, $block);

           if($index === 0) {
               $this->assertEquals(3, $block->day_of_week);
               $this->assertEquals("16:00", $block->starts);
               $this->assertEquals("17:00", $block->ends);
           }

            if($index === 1) {
                $this->assertEquals(5, $block->day_of_week);
                $this->assertEquals("18:00", $block->starts);
                $this->assertEquals("20:00", $block->ends);
            }
        });
    }

    /**
     *@test
     */
    public function setting_lesson_blocks_clears_previous_ones()
    {
        $course = factory(Course::class)->create();

        $course->setLessonBlocks([
            [
                'day_of_week' => 1,
                'starts'      => '16:00',
                'ends'        => '17:00',
            ]
        ]);

        $this->assertCount(1, $course->fresh()->lessonBlocks);

        $blocks = $course->setLessonBlocks([
            [
                'day_of_week' => 3,
                'starts'      => '16:00',
                'ends'        => '17:00',
            ],
            [
                'day_of_week' => 5,
                'starts'      => '18:00',
                'ends'        => '20:00',
            ]
        ]);

        $this->assertCount(2, $course->fresh()->lessonBlocks);
        $this->assertCount(2, $blocks);

        $blocks->each(function($block, $index) {
            $this->assertInstanceOf(LessonBlock::class, $block);

            if($index === 0) {
                $this->assertEquals(3, $block->day_of_week);
                $this->assertEquals("16:00", $block->starts);
                $this->assertEquals("17:00", $block->ends);
            }

            if($index === 1) {
                $this->assertEquals(5, $block->day_of_week);
                $this->assertEquals("18:00", $block->starts);
                $this->assertEquals("20:00", $block->ends);
            }
        });
    }
}
