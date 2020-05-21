<?php

namespace App\Locations;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = ['name'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    public function addArea($name)
    {
        return $this->areas()->create(['name' => $name]);
    }

    public function fullDelete()
    {
        $this->areas()->delete();
        $this->delete();
    }
}
