<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'category',
        'status',
        'is_confidential',
        'expiry_date'
    ];

    protected $casts = [
        'is_confidential' => 'boolean',
        'expiry_date' => 'date'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getFileUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->file_path);
    }

    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function isExpiringSoon(): bool
    {
        return $this->expiry_date && $this->expiry_date->diffInDays(now()) <= 30;
    }

    public function getCategoryIconAttribute(): string
    {
        return match($this->category) {
            'birth_certificate' => 'bi-file-earmark-text',
            'medical_record' => 'bi-heart-pulse',
            'legal_document' => 'bi-shield-check',
            'photo' => 'bi-image',
            'report' => 'bi-file-earmark-bar-graph',
            'assessment' => 'bi-clipboard-data',
            'consent_form' => 'bi-file-earmark-check',
            'other' => 'bi-file-earmark',
            default => 'bi-file-earmark'
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match($this->category) {
            'birth_certificate' => 'text-primary',
            'medical_record' => 'text-danger',
            'legal_document' => 'text-success',
            'photo' => 'text-info',
            'report' => 'text-warning',
            'assessment' => 'text-secondary',
            'consent_form' => 'text-dark',
            'other' => 'text-muted',
            default => 'text-muted'
        };
    }
} 