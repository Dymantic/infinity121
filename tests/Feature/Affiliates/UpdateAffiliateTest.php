<?php


namespace Tests\Feature\Affiliates;


use App\Affiliates\Affiliate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateAffiliateTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_an_affiliate()
    {
        $this->withoutExceptionHandling();

        $affiliate = factory(Affiliate::class)->create([
            "name" => ['en' => "", "zh" => "", 'jp' => 'jp old name'],
            "description" => ['en' => "", "zh" => "", 'jp' => 'jp old description'],
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/affiliates/{$affiliate->id}", [
            "name" => ["en" => 'new name', 'zh' => "zh new name"],
            "description" => ["en" => 'new description', 'zh' => "zh new description"],
            "link" => 'https://newlink.test',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('affiliates', [
            'id' => $affiliate->id,
            'link' => 'https://newlink.test',
        ]);

        $this->assertDatabaseHasWithTranslations('en', 'affiliates', [
            'name' => 'new name',
            'description' => 'new description',
        ], ['id' => $affiliate->id]);

        $this->assertDatabaseHasWithTranslations('zh', 'affiliates', [
            'name' => 'zh new name',
            'description' => 'zh new description',
        ], ['id' => $affiliate->id]);

        $this->assertDatabaseHasWithTranslations('jp', 'affiliates', [
            'name' => 'jp old name',
            'description' => 'jp old description',
        ], ['id' => $affiliate->id]);
    }

    /**
     *@test
     */
    public function the_name_requires_content_for_some_language()
    {
        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/affiliates/{$affiliate->id}", [
            'name' => ['en' => '', 'zh' => '', 'jp' => ''],
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /**
     *@test
     */
    public function the_link_must_be_a_url()
    {
        $affiliate = factory(Affiliate::class)->create();

        $response = $this->asAdmin()->postJson("/admin/api/affiliates/{$affiliate->id}", [
            'name' => ['en' => '', 'zh' => '', 'jp' => 'test name'],
            'link' => 'not-a-url',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('link');
    }
}
