<?php

namespace App\Locations;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = ['name'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'area_name' => $this->name,
            'region_name' => $this->region->name,
            'region_id' => $this->region_id,
            'country_name' => $this->region->country->name,
            'country_id' => $this->region->country->id,
        ];
    }
}
