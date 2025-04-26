<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gender extends Model
{
    use HasFactory;

    protected $table = 'gender'; // Ensure this matches the table name
    protected $fillable = ['gender_name']; // Use the correct column name

    /**
     * Define the relationship with the User model.
     * A gender can be associated with many users.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'gender_id'); // Ensure 'gender_id' matches the foreign key in the users table
    }
}