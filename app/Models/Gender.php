<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gender extends Model
{
    use HasFactory;

    protected $table = 'gender'; // Updated to match the migration
    protected $fillable = ['gender_name']; // Use the correct column name

    /**
     * Define the relationship with the User model.
     * A gender can be associated with many users.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'gender_id'); // Ensure 'gender_id' matches the foreign key in the users table
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'clientgender');
    }
}