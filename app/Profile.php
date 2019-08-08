<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use Translatable;

    protected $guarded = [];

    protected $casts = ['bio' => 'array'];

    public $translatable = ['bio'];
}
