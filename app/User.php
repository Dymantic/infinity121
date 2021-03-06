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
        'name',
        'email',
        'password',
        'is_admin',
        'is_teacher',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at'    => 'datetime',
        'is_admin'             => 'boolean',
        'is_teacher'           => 'boolean',
        'removed'              => 'boolean',
        'receive_admin_emails' => 'boolean',
    ];

    public function scopeActive($query)
    {
        $query->where('removed', false);
    }

    public function scopeAdmins($query)
    {
        return $query->where('is_admin', true);
    }

    public function scopeReceivesAdminEmails($query)
    {
        return $query->where([
            ['is_admin', true],
            ['receive_admin_emails', true]
        ]);
    }

    public static function addAdmin($attributes)
    {
        $admin = static::create([
            'name'       => $attributes['name'],
            'email'      => $attributes['email'],
            'is_admin'   => true,
            'is_teacher' => $attributes['is_teacher'] ?? false,
            'password'   => Hash::make($attributes['password']),
        ]);

        $admin->makeProfile();

        return $admin;
    }

    public static function addTeacher($attributes)
    {
        $teacher = static::create([
            'name'       => $attributes['name'],
            'email'      => $attributes['email'],
            'is_admin'   => false,
            'is_teacher' => true,
            'password'   => Hash::make($attributes['password']),
        ]);

        $teacher->makeProfile();

        return $teacher;
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function makeProfile()
    {
        if (!$this->profile) {
            $this->profile()->create([
                'name'             => $this->name,
                'bio'              => ['en' => ''],
                'spoken_languages' => ['en']
            ]);
        }

        return $this->fresh()->profile;
    }

    public function updatePassword($password)
    {
        $this->password = Hash::make($password);
        $this->save();
    }

    public function retire()
    {
        if ($this->profile) {
            $this->profile->retract();
        }

        $this->removed = true;
        $this->save();
    }

    public function subscribeToAdminEmails()
    {
        $this->receive_admin_emails = true;
        $this->save();
    }

    public function unsubscribeFromAdminEmails()
    {
        $this->receive_admin_emails = false;
        $this->save();
    }
}
