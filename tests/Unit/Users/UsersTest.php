<?php

namespace Tests\Unit\Users;

use App\Profile;
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

    /**
     *@test
     */
    public function has_a_admin_scope()
    {
        $adminA = factory(User::class)->state('admin-only')->create();
        $adminB = factory(User::class)->state('admin-only')->create();
        $teacher = factory(User::class)->state('teacher-only')->create();

        $admins = User::admins()->get();

        $this->assertCount(2, $admins);
        $this->assertTrue($admins->contains($adminA));
        $this->assertTrue($admins->contains($adminB));
        $this->assertFalse($admins->contains($teacher));
    }

    /**
     *@test
     */
    public function can_be_retired()
    {
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->create([
            'user_id' => $user->id,
            'is_public' => true,
        ]);

        $this->assertFalse($user->removed);
        $this->assertTrue($profile->is_public);

        $user->retire();

        $user = $user->fresh();

        $this->assertTrue($user->removed);
        $this->assertFalse($profile->fresh()->is_public);

    }

    /**
     *@test
     */
    public function have_active_scope()
    {
        $userA = factory(User::class)->create();
        $userB = factory(User::class)->create();

        $userB->retire();

        $scoped = User::active()->get();

        $this->assertCount(1, $scoped);
        $this->assertTrue($scoped->first()->is($userA));
    }
}
