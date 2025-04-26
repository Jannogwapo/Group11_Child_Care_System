<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';

    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_description',
        'activity_location',
        'activity_date',
        'activity_image'
    ];

    protected $dates = [
        'activity_date',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 