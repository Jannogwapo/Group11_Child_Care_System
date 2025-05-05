<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccessLog extends Model
{
    // Specify the table name if it doesn't follow Laravel's naming convention
    protected $table = 'access_logs';

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'status', // e.g., 'pending', 'accept', 'denied'
        'created_at',
        'updated_at',
    ];

    // Define relationships (if any)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
