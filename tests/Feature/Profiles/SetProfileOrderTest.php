<?php


namespace Tests\Feature\Profiles;


use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetProfileOrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function set_the_order_for_profiles()
    {
        $this->withoutExceptionHandling();

        $teacherA = factory(Profile::class)->create();
        $teacherB = factory(Profile::class)->create();
        $teacherC = factory(Profile::class)->create();
        $teacherD = factory(Profile::class)->create();

        $order = [$teacherC->id, $teacherB->id, $teacherD->id, $teacherA->id];

        $response = $this->asAdmin()->postJson("/admin/api/profiles-order", [
            'order' => $order,
        ]);
        $response->assertSuccessful();

        $this->assertEquals(1, $teacherC->fresh()->position);
        $this->assertEquals(2, $teacherB->fresh()->position);
        $this->assertEquals(3, $teacherD->fresh()->position);
        $this->assertEquals(4, $teacherA->fresh()->position);
    }

    /**
     *@test
     */
    public function order_cannot_be_set_by_non_admin()
    {
        $teacherA = factory(Profile::class)->create();
        $teacherB = factory(Profile::class)->create();
        $teacherC = factory(Profile::class)->create();
        $teacherD = factory(Profile::class)->create();

        $order = [$teacherC->id, $teacherB->id, $teacherD->id, $teacherA->id];

        $response = $this->asTeacher()->postJson("/admin/api/profiles-order", [
            'order' => $order,
        ]);
        $response->assertForbidden();
    }

    /**
     *@test
     */
    public function the_order_is_required()
    {
        $response = $this->asAdmin()->postJson("/admin/api/profiles-order", [
            'order' => null,
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order');
    }

    /**
     *@test
     */
    public function the_order_must_be_an_array()
    {
        $response = $this->asAdmin()->postJson("/admin/api/profiles-order", [
            'order' => 'not-an-array',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order');
    }

    /**
     *@test
     */
    public function the_order_must_contain_on_valid_profile_ids()
    {
        $this->assertNull(Profile::find(99));

        $response = $this->asAdmin()->postJson("/admin/api/profiles-order", [
            'order' => [99],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('order.0');
    }
}
