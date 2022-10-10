<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function province()
    {
        return $this->belongsTo(Region::class)
            ->where('level', Region::LEVEL_PROVINCE);
    }

    public function city()
    {
        return $this->belongsTo(Region::class)
            ->where('level', Region::LEVEL_CITY);
    }

    public function dist()
    {
        return $this->belongsTo(Region::class)
            ->where('level', Region::LEVEL_DIST);
    }
}
