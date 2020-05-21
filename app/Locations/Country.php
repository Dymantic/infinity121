<?php

namespace App\Locations;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name'];

    public function regions()
    {
        return $this->hasMany(Region::class);
    }

    public function addRegion($name)
    {
        return $this->regions()->create([
            'name' => $name,
        ]);
    }

    public function fullDelete()
    {
        $this->regions->map->fullDelete();
        $this->delete();
    }
}
