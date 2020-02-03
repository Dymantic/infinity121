<?php

namespace Tests\Feature\Affiliates;

use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function create_a_new_affiliate()
    {
        $this->withoutExceptionHandling();

        $affiliate_data = [
            'name' => ['en' => 'test name', 'zh' => 'zh test name'],
            'description' => ['en' => 'test description', 'zh' => 'zh test description'],
            'link' => 'https://test.test'
        ];

        $response = $this->asAdmin()->postJson("/admin/api/affiliates", $affiliate_data);
        $response->assertStatus(201);

        $this->assertDatabaseHasWithTranslations('en', 'affiliates', [
            'name' => 'test name',
            'description' => 'test description',
        ]);

        $this->assertDatabaseHasWithTranslations('zh', 'affiliates', [
            'name' => 'zh test name',
            'description' => 'zh test description',
        ]);

        $this->assertDatabaseHas('affiliates', ['link' => 'https://test.test']);
    }

    /**
     *@test
     */
    public function an_affiliate_can_only_be_added_as_an_admin()
    {
        $affiliate_data = [
            'name' => ['en' => 'test name', 'zh' => 'zh test name'],
            'description' => ['en' => 'test description', 'zh' => 'zh test description'],
            'link' => 'https://test.test'
        ];

        $response = $this->asTeacher()->postJson("/admin/api/affiliates", $affiliate_data);
        $response->assertStatus(403);

        $this->assertCount(0, Affiliate::all());
    }

    /**
     *@test
     */
    public function the_name_is_required_in_at_least_one_language()
    {
        $response = $this->asAdmin()->postJson("/admin/api/affiliates", [
            "name" => ["en" => '', "zh" => "", "jp" => ""]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function the_link_must_be_a_valid_url()
    {
        $response = $this->asAdmin()->postJson("/admin/api/affiliates", [
            'name' => ['en' => 'test name'],
            'link' => 'not-a-valid-url'
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('link');
    }
}
