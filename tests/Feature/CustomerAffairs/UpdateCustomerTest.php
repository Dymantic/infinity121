<?php


namespace Tests\Feature\CustomerAffairs;


use App\CustomerAffairs\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class UpdateCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_existing_customer()
    {
        $this->withoutExceptionHandling();

        $customer = factory(Customer::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/customers/{$customer->id}", [
            'name' => 'new test name',
            'email' => 'new@test.test',
            'phone' => 'new test phone',
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'new test name',
            'email' => 'new@test.test',
            'phone' => 'new test phone',
        ]);
    }

    /**
     *@test
     */
    public function the_name_is_required()
    {
        $this->assertFieldIsInvalid(['name' => null]);
    }

    /**
     *@test
     */
    public function the_email_is_required_without_the_phone()
    {
        $this->assertFieldIsInvalid([
            'email' => null,
            'phone' => null,
        ]);

        $this->assertFieldsValid([
            'name' => 'new test name',
            'phone' => 'new test phone',
        ]);
    }

    /**
     *@test
     */
    public function the_email_must_be_a_valid_email()
    {
        $this->assertFieldIsInvalid(['email' => 'not-a-real-email']);
    }

    /**
     *@test
     */
    public function the_phone_is_required_without_the_email()
    {
        $this->assertFieldIsInvalid([
            'phone' => null,
            'email' => null,
        ]);

        $this->assertFieldsValid([
            'name' => 'new test name',
            'phone' => 'new@test.test',
        ]);
    }

    private function assertFieldsValid($fields)
    {
        $customer = factory(Customer::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/customers/{$customer->id}", $fields);
        $response->assertSuccessful();
    }

    private function assertFieldIsInvalid($field)
    {
        $valid = [
            'name' => 'new test name',
            'email' => 'new@test.test',
            'phone' => 'new test phone',
        ];
        $customer = factory(Customer::class)->create();

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/customers/{$customer->id}", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
