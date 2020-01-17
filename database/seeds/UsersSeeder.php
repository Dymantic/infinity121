<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $me = \App\User::create([
            'name' => 'Mooz Joyner',
            'email' => 'joyner.michael@gmail.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
        $me->makeProfile();

        $admins = factory(\App\User::class, 2)->state('admin-only')->create();
        $teachers = factory(\App\User::class, 6)->state('teacher-only')->create();

        $teachers->each(function($teacher) {
            factory(\App\Profile::class)->create(['user_id' => $teacher->id]);
        });
    }
}
