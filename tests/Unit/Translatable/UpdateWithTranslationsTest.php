<?php

namespace Tests\Unit\Translatable;

use App\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateWithTranslationsTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_model_with_trait_can_be_updated_with_translations()
    {
        $profile = factory(Profile::class)->create(['bio' => ['zh' => 'chinese bio']]);

        $profile->updateWithTranslations([
            'name' => 'New test name',
            'bio' => ['en' => 'new en bio'],
        ]);

        $this->assertDatabaseHas('profiles', [
            'id' => $profile->id,
            'name' => 'New test name',
        ]);

        $this->assertDatabaseHasWithTranslations('en', 'profiles', [
            'bio' => 'new en bio',
        ]);

        $this->assertDatabaseHasWithTranslations('zh', 'profiles', [
            'bio' => 'chinese bio',
        ]);

    }
}