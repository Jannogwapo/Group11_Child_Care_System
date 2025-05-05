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

        // Get the admin role ID
        $adminRoleId = DB::table('user_role')->where('role_name', 'admin')->first()->id;

        // Create the admin user
        User::create([
            'name' => 'Allyza Faith B. Rodrigo',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'), // Set password to 12345678
            'role_id' => $adminRoleId,
            'gender_id' => $genderId,
            'access_id' => 2, 
            'email_verified_at' => now(),
        ]);

       
    }
}