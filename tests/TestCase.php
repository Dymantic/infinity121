<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mcamara\LaravelLocalization\LaravelLocalization;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asAdmin()
    {
        $admin = factory(User::class)->states('admin-only')->create();

        return $this->actingAs($admin);
    }

    public function asTeacher()
    {
        $teacher = factory(User::class)->states('teacher-only')->create();

        return $this->actingAs($teacher);
    }

    public function asGuest()
    {
        $this->assertGuest();
        return $this;
    }

    public function assertDatabaseHasWithTranslations($lang, $table, $data, $untranslated = [])
    {
        $translatables = collect($data)->flatMap(function($value, $key) use ($lang) {
            return ["{$key}->{$lang}" => $value];
        })->all();

        $this->assertDatabaseHas($table, array_merge($translatables, $untranslated));

    }

    protected function refreshApplicationWithLocale($locale)
    {
        self::tearDown();
        putenv(LaravelLocalization::ENV_ROUTE_KEY . '=' . $locale);
        self::setUp();
    }

    protected function tearDown(): void
    {
        putenv(LaravelLocalization::ENV_ROUTE_KEY);
        parent::tearDown();
    }
}
