<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'blood_type',
        'allergies',
        'medical_conditions',
        'medications',
        'dietary_restrictions',
        'special_needs',
        'immunization_history',
        'emergency_medical_info',
        'primary_physician',
        'physician_contact',
        'hospital_preference',
        'notes'
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function hasAllergies(): bool
    {
        return !empty($this->allergies);
    }

    public function hasMedicalConditions(): bool
    {
        return !empty($this->medical_conditions);
    }

    public function hasSpecialNeeds(): bool
    {
        return !empty($this->special_needs);
    }

    public function getMedicalAlertAttribute(): bool
    {
        return $this->hasAllergies() || $this->hasMedicalConditions() || $this->hasSpecialNeeds();
    }
} 