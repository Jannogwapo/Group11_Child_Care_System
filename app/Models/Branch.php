<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $table = 'branch';

    protected $fillable = [
        'branchName',
        
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'branch_id');
    }
} 