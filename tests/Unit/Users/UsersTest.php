<?php

namespace Tests\Unit\Users;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /**
     *@test
     */
    public function add_an_admin_user()
    {
        $admin = User::addAdmin([
            'name' => 'test admin',
            'email' => 'test@test.test',
            'is_teacher' => false,
            'password' => 'secret'
        ])->fresh();

        $this->assertEquals('test admin', $admin->name);
        $this->assertEquals('test@test.test', $admin->email);

        $this->assertTrue($admin->is_admin);
        $this->assertFalse($admin->is_teacher);

        $this->assertTrue(Hash::check('secret', $admin->password));
    }

    /**
     *@test
     */
    public function add_a_teacher()
    {
        $teacher = User::addTeacher([
            'name' => 'test teacher',
            'email' => 'test@test.test',
            'password' => 'secret'
        ])->fresh();

        $this->assertEquals('test teacher', $teacher->name);
        $this->assertEquals('test@test.test', $teacher->email);

        $this->assertFalse($teacher->is_admin);
        $this->assertTrue($teacher->is_teacher);

        $this->assertTrue(Hash::check('secret', $teacher->password));
    }

    /**
     *@test
     */
    public function update_a_password()
    {
        $user = factory(User::class)->create();

        $user->updatePassword('new_password');

        $this->assertTrue(Hash::check('new_password', $user->fresh()->password));
    }
}