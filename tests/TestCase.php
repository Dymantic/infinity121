<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function asAdmin()
    {
        $admin = factory(User::class)->states('admin-only')->create();

        return $this->actingAs($admin);
    }

    public function assertDatabaseHasWithTranslations($lang, $table, $data)
    {
        $translatables = collect($data)->flatMap(function($value, $key) use ($lang) {
            return ["{$key}->{$lang}" => $value];
        })->all();

        $this->assertDatabaseHas($table, $translatables);
    }
}
