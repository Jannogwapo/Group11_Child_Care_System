<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmergencyContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'contact_name',
        'relationship',
        'phone_number',
        'email',
        'address',
        'priority',
        'is_available_24_7',
        'special_instructions'
    ];

    protected $casts = [
        'is_available_24_7' => 'boolean'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'primary' => 'text-danger font-weight-bold',
            'secondary' => 'text-warning',
            'tertiary' => 'text-info',
            default => 'text-muted'
        };
    }

    public function getPriorityIconAttribute(): string
    {
        return match($this->priority) {
            'primary' => 'bi-exclamation-triangle-fill',
            'secondary' => 'bi-exclamation-circle',
            'tertiary' => 'bi-info-circle',
            default => 'bi-person'
        };
    }
} 