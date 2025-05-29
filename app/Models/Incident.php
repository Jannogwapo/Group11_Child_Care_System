<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        'incident_type',
        'incident_description',
        'incident_date',
        'incident_image',
        'user_id',
        // add other fields as needed
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(IncidentImage::class);
    }
}
