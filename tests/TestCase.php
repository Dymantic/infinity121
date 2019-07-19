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
}
