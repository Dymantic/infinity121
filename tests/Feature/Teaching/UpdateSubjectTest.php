<?php


namespace Tests\Feature\Teaching;


use App\Teaching\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateSubjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function update_an_existing_subject()
    {
        $this->withoutExceptionHandling();

        $subject = factory(Subject::class)->create([
            'title' => ['en' => 'test title'],
            'description' => ['en' => ''],
            'writeup' => ['en' => ''],
        ]);

        $response = $this->asAdmin()->postJson("/admin/api/subjects/{$subject->id}", [
            'title' => ['en' => 'new title', 'zh' => 'xinde mingze'],
            'description' => ['en' => 'new description', 'zh' => 'xinde description'],
            'writeup' => ['en' => 'new writeup', 'zh' => 'xinde writeup'],
        ]);
        $response->assertStatus(200);

        $this->assertDatabaseHasWithTranslations('en', 'subjects', [
            'title' => 'new title',
            'description' => 'new description',
            'writeup' => 'new writeup',
        ], [
            'id' => $subject->id,
        ]);

        $this->assertDatabaseHasWithTranslations('zh', 'subjects', [
            'title' => 'xinde mingze',
            'description' => 'xinde description',
            'writeup' => 'xinde writeup',
        ], [
            'id' => $subject->id,
        ]);
    }

    /**
     *@test
     */
    public function can_only_be_updated_by_admin()
    {
        $subject = factory(Subject::class)->create([
            'title' => ['en' => 'test title'],
            'description' => ['en' => ''],
            'writeup' => ['en' => ''],
        ]);

        $response = $this->asTeacher()->postJson("/admin/api/subjects/{$subject->id}", [
            'title' => ['en' => 'new title', 'zh' => 'xinde mingze'],
        ]);
        $response->assertStatus(403);
    }
}
