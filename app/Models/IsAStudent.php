<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IsAStudent extends Model
{
    protected $table = 'isAStudent';

    protected $fillable = [
        'status',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'isAStudent');
    }
}
