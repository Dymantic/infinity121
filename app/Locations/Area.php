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
}
