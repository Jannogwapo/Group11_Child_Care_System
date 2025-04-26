<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first gender ID
        $genderId = DB::table('gender')->first()->id;

        // Create users for each role
        $roles = DB::table('user_role')->get();
        
        foreach ($roles as $role) {
            User::create([
                'name' => ucfirst($role->role_name) . ' User',
                'email' => strtolower(str_replace(' ', '', $role->role_name)) . '@example.com',
                'password' => bcrypt('password'),
                'role_id' => $role->id,
                'gender_id' => $genderId,
                'email_verified_at' => now(),
            ]);
        }
    }
} 