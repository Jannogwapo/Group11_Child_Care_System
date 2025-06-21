<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Hearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'branch_id',
        'user_id',
        'hearing_date',
        'time',
        'status',
        'notes',
        'edit_count',
        'reminder_code',

    ];

    protected $casts = [
        'hearing_date' => 'date',
        'next_hearing_date' => 'date',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }


    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'completed' => 'green',
            'postponed' => 'yellow',
            default => 'gray'
        };
    }

    public function getFormattedDateAttribute()
    {
        return $this->hearing_date ? Carbon::parse($this->hearing_date)->format('F j, Y') : 'N/A';
    }

    public function getFormattedTimeAttribute()
    {
        return $this->time ? Carbon::parse($this->time)->format('g:i A') : 'N/A';
    }

    public function scopeUpcoming($query)
    {
        return $query->where('hearing_date', '>=', now()->format('Y-m-d'))
                    ->where('status', 'scheduled')
                    ->orderBy('hearing_date', 'asc')
                    ->orderBy('time', 'asc');
    }

    public function scopeCompleted($query)
    {
        return $query->where('hearing_date', '<', now()->format('Y-m-d'))
                    ->where('status', 'completed')
                    ->orderBy('hearing_date', 'desc');
    }
}