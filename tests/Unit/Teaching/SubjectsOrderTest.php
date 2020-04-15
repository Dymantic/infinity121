<?php


namespace Tests\Unit\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubjectsOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function subjects_have_a_nullable_position()
    {
        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create(['position' => 4]);

        $this->assertNull($subjectA->position);
        $this->assertEquals(4, $subjectB->position);
    }

    /**
     *@test
     */
    public function subjects_can_be_scoped_into_ordered_by_position_with_null_last()
    {
        $subjectA = factory(Subject::class)->create(['position' => 3]);
        $subjectB = factory(Subject::class)->create(['position' => null]);
        $subjectC = factory(Subject::class)->create(['position' => 2]);
        $subjectD = factory(Subject::class)->create(['position' => 1]);
        $subjectE = factory(Subject::class)->create(['position' => 4]);

        $ordered = Subject::inOrder()->get();

        $this->assertTrue($ordered[0]->is($subjectD));
        $this->assertTrue($ordered[1]->is($subjectC));
        $this->assertTrue($ordered[2]->is($subjectA));
        $this->assertTrue($ordered[3]->is($subjectE));
        $this->assertTrue($ordered[4]->is($subjectB));
    }

    /**
     *@test
     */
    public function subjects_order_can_be_set_by_passing_ordered_array_of_ids()
    {
        $subjectA = factory(Subject::class)->create();
        $subjectB = factory(Subject::class)->create();
        $subjectC = factory(Subject::class)->create();
        $subjectD = factory(Subject::class)->create();

        Subject::setOrder([$subjectC->id, $subjectB->id, $subjectD->id, $subjectA->id]);

        $this->assertEquals(1, $subjectC->fresh()->position);
        $this->assertEquals(2, $subjectB->fresh()->position);
        $this->assertEquals(3, $subjectD->fresh()->position);
        $this->assertEquals(4, $subjectA->fresh()->position);

    }
}
