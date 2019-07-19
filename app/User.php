<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'name', 'email', 'password', 'is_admin', 'is_teacher',
    ];


    protected $hidden = [
        'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_teacher' => 'boolean'
    ];

    public static function addAdmin($attributes)
    {
        return static::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'is_admin' => true,
            'is_teacher' => $attributes['is_teacher'] ?? false,
            'password' => Hash::make($attributes['password']),
        ]);
    }

    public static function addTeacher($attributes)
    {
        return static::create([
            'name' => $attributes['name'],
            'email' => $attributes['email'],
            'is_admin' => false,
            'is_teacher' => true,
            'password' => Hash::make($attributes['password']),
        ]);
    }

    public function updatePassword($password)
    {
        $this->password = Hash::make($password);
        $this->save();
    }
}
