<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cases extends Model
{
    protected $table = 'case';

    protected $fillable = [
        'case_name',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'case_id');
    }
} 