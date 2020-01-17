<?php

namespace Tests\Feature\Teaching;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateSubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function a_new_subject_can_be_created_with_english_title()
    {
        $this->withoutExceptionHandling();

        $response = $this->asAdmin()->postJson("/admin/api/subjects", [
            'title' => 'Test subject',
        ]);
        $response->assertStatus(201);

        $this->assertDatabaseHasWithTranslations('en', 'subjects', [
            'title' => 'Test subject',
            'description' => '',
            'writeup' => ''
        ]);
    }

    /**
     *@test
     */
    public function the_title_is_required()
    {
        $response = $this->asAdmin()->postJson("/admin/api/subjects", [
            'title' => '',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    /**
     *@test
     */
    public function subject_can_only_be_added_by_admin()
    {
        $response = $this->asTeacher()->postJson("/admin/api/subjects", [
            'title' => 'test title',
        ]);
        $response->assertStatus(403);
    }
}
