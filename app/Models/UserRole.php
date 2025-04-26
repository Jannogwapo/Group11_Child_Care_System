<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    use HasFactory;

    protected $table = 'user_role'; // Ensure the table name matches the database table
    protected $fillable = ['role_name'];

    /**
     * Define the relationship with the User model.
     * A role can be associated with many users.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}