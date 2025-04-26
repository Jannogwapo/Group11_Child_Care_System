<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class IsAPwd extends Model
{
    //
    protected $table = 'isAPwd';

    protected $fillable = [
        'isAPwd',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'isAPwd');
    }
}
