<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Region extends Model
{
    use SoftDeletes;

    const LEVEL_COUNTRY  = 1;
    const LEVEL_PROVINCE = 2;
    const LEVEL_CITY     = 3;
    const LEVEL_DIST     = 4;

    const CHINA = 86;

    protected $hidden = [
        'parent_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function scopeProvince()
    {
        return $this->where('level', Region::LEVEL_PROVINCE);
    }

    public function scopeCity()
    {
        return $this->where('level', Region::LEVEL_CITY);
    }

    public function scopeDist()
    {
        return $this->where('level', Region::LEVEL_DIST);
    }

    public static function provinces($country = 86)
    {
        return Region::where('level', Region::LEVEL_PROVINCE)
            ->where('parent_id', (int) $country)
            ->get();
    }

    public static function cities(Region $province)
    {
        return Region::where('level', Region::LEVEL_CITY)
            ->where('parent_id', $province->id)
            ->get();
    }

    public static function dists(Region $city)
    {
        return Region::where('level', Region::LEVEL_DIST)
            ->where('parent_id', $city->id)
            ->get();
    }
}
