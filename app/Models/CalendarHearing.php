<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CalendarHearing extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'user_id',
        'client_id',
        'judge_id',
        'hearing_date',
        'time',
        'status',
        'notes'
    ];

    protected $casts = [
        'hearing_date' => 'date',
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function judge(): BelongsTo
    {
        return $this->belongsTo(Judge::class);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'completed' => 'green',
            'postponed' => 'yellow',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    // Helper method to get formatted date
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->hearing_date)->format('F j, Y');
    }

    // Helper method to get formatted time
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->time)->format('g:i A');
    }
} 