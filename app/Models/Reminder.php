<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'title',
        'description',
        'type',
        'reminder_date',
        'frequency',
        'priority',
        'is_completed',
        'is_active',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'reminder_date' => 'datetime',
        'completed_at' => 'datetime',
        'is_completed' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        return $this->reminder_date->isPast() && !$this->is_completed;
    }

    public function isDueToday(): bool
    {
        return $this->reminder_date->isToday() && !$this->is_completed;
    }

    public function isDueSoon(): bool
    {
        return $this->reminder_date->diffInDays(now()) <= 3 && !$this->is_completed;
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'text-danger font-weight-bold',
            'high' => 'text-warning font-weight-bold',
            'medium' => 'text-info',
            'low' => 'text-muted',
            default => 'text-muted'
        };
    }

    public function getPriorityIconAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'bi-exclamation-triangle-fill',
            'high' => 'bi-exclamation-circle-fill',
            'medium' => 'bi-info-circle',
            'low' => 'bi-info',
            default => 'bi-info'
        };
    }

    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'hearing' => 'bi-calendar-event',
            'medical_checkup' => 'bi-heart-pulse',
            'document_expiry' => 'bi-file-earmark-excel',
            'follow_up' => 'bi-arrow-repeat',
            'assessment' => 'bi-clipboard-data',
            'emergency_contact_update' => 'bi-person-lines-fill',
            'custom' => 'bi-bell',
            default => 'bi-bell'
        };
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now()
        ]);
    }

    public function reschedule(Carbon $newDate): void
    {
        $this->update([
            'reminder_date' => $newDate,
            'is_completed' => false,
            'completed_at' => null
        ]);
    }
} 