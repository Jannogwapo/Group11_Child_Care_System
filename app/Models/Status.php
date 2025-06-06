<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    protected $table = 'status';

    protected $fillable = [
        'status_name',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'status_id');
    }
} 