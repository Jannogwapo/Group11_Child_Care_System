<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Judge extends Model
{
    protected $fillable = [
        'judgeName',
        'branch_id',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function hearings(): HasMany
    {
        return $this->hasMany(Hearing::class);
    }
    
} 