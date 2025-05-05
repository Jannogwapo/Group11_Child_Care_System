<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PhilippineCity extends Model
{
    protected $fillable = ['name', 'code', 'province_id'];

    public function province(): BelongsTo
    {
        return $this->belongsTo(PhilippineProvince::class, 'province_id');
    }

    public function barangays(): HasMany
    {
        return $this->hasMany(PhilippineBarangay::class, 'city_id');
    }
} 