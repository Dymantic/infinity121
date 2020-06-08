<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function delete_an_existing_customer()
    {
        $this->withoutExceptionHandling();

        $customer = factory(Customer::class)->create();

        $response = $this->asAdmin()->deleteJson("/admin/api/customers/{$customer->id}");
        $response->assertSuccessful();

        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
