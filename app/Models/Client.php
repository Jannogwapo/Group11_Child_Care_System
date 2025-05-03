<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'clientLastName',
        'clientFirstName',
        'clientMiddleName',
        'clientBirthdate',
        'clientAge',
        'clientgender',
        'clientaddress',
        'clientguardian',
        'clientguardianrelationship',
        'guardianphonenumber',
        'case_id',
        'clientdateofadmission',
        'status_id',
        'isAStudent',
        'branch_id',
        'isAPwd',
        'user_id',
        'location_id',


    ];

   /** @var list<string> */
    protected $dates = [
        'clientBirthdate',
        'clientdateofadmission',
        'created_at',
        'updated_at'
    ];

    public function gender(): BelongsTo
    {
        return $this->belongsTo(Gender::class, 'clientgender');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function case(): BelongsTo
    {
        return $this->belongsTo(Cases::class, 'case_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isAStudent(): BelongsTo
    {
        return $this->belongsTo(IsAStudent::class, 'isAStudent');
    }

    public function isAPwd(): BelongsTo
    {
        return $this->belongsTo(IsAPwd::class, 'isAPwd');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    

}

            
          


