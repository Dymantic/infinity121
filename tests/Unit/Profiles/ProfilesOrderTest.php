<?php


namespace Tests\Unit\Profiles;


use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilesOrderTest extends TestCase
{
    use RefreshDatabase;


    /**
     *@test
     */
    public function profiles_have_a_nullable_position()
    {
        $profileA = factory(Profile::class)->create(['position' => 3]);
        $profileB = factory(Profile::class)->create(['position' => null]);

        $this->assertEquals(3, $profileA->position);
        $this->assertNull($profileB->position);

    }

    /**
     *@test
     */
    public function profiles_can_be_scoped_to_ordered_by_position()
    {
        $profileA = factory(Profile::class)->create(['position' => 4]);
        $profileB = factory(Profile::class)->create(['position' => 1]);
        $profileC = factory(Profile::class)->create(['position' => null]);
        $profileD = factory(Profile::class)->create(['position' => 6]);

        $ordered = Profile::inOrder()->get();

        $this->assertTrue($ordered[0]->is($profileB));
        $this->assertTrue($ordered[1]->is($profileA));
        $this->assertTrue($ordered[2]->is($profileD));
        $this->assertTrue($ordered[3]->is($profileC));
    }

    /**
     *@test
     */
    public function set_order_by_passing_ordered_array_of_ids()
    {
        $profileA = factory(Profile::class)->create();
        $profileB = factory(Profile::class)->create();
        $profileC = factory(Profile::class)->create();
        $profileD = factory(Profile::class)->create();

        Profile::setOrder([$profileB->id, $profileD->id, $profileC->id, $profileA->id, 99]);

        $this->assertEquals(1, $profileB->fresh()->position);
        $this->assertEquals(2, $profileD->fresh()->position);
        $this->assertEquals(3, $profileC->fresh()->position);
        $this->assertEquals(4, $profileA->fresh()->position);
    }
}
