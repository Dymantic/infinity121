<?php

namespace Tests\Feature\CustomerAffairs;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AddCustomerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_a_new_customer()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/customers", [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone'
        ]);
        $response->assertSuccessful();

        $this->assertDatabaseHas('customers', [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone'
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
    public function the_email_is_required_without_the_phone_number()
    {
        $this->assertFieldIsInvalid([
            'email' => null,
            'phone' => null,
        ]);

        $this->assertFieldsAreValid([
            'name' => 'test name',
            'email' => 'test@test.test',
        ]);
    }

    /**
     *@test
     */
    public function the_email_must_be_a_valid_email()
    {
        $this->assertFieldIsInvalid(['email' => 'not-an-email']);
    }

    /**
     *@test
     */
    public function the_phone_number_is_required_without_the_email()
    {
        $this->assertFieldIsInvalid([
            'email' => null,
            'phone' => null,
        ]);

        $this->assertFieldsAreValid([
            'phone' => 'test phone',
            'name' => 'test name',
        ]);
    }

    private function assertFieldsAreValid($fields)
    {
        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/customers", $fields);
        $response->assertSuccessful();
    }

    private function assertFieldIsInvalid($field)
    {
        $valid = [
            'name' => 'test name',
            'email' => 'test@test.test',
            'phone' => 'test phone'
        ];

        $response = $this
            ->asAdmin()
            ->postJson("/admin/api/customers", array_merge($valid, $field));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response->assertJsonValidationErrors(array_key_first($field));
    }
}
