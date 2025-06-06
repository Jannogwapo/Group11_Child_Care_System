<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Location extends Model
{
    //
    protected $table = 'location';

    protected $fillable = [
        'location',
    ];      

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'location_id');
    }
    
    
}
