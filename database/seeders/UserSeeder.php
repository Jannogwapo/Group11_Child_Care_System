<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $gender = DB::table('gender')->first();
        $adminRole = DB::table('user_role')->where('role_name', 'admin')->first();
        $socialWorkerRole = DB::table('user_role')->where('role_name', 'social worker')->first();
        $it = DB::table('user_role')->where('role_name', 'it tech')->first();

        if (!$gender || !$adminRole || !$socialWorkerRole) {
            throw new \Exception('Missing required seed data: genders or user_role table is empty.');
        }

        User::create([
            'name' => 'Allyza Faith B. Rodrigo',
            'email' => 'admin@example.com',
            'password' => bcrypt('12345678'),
            'role_id' => $adminRole->id,
            'gender_id' => $gender->id,
            'access_id' => 2,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'james',
            'email' => 'it@example.com',
            'password' => bcrypt('12345678'),
            'role_id' => $it->id,
            'gender_id' => $gender->id,
            'access_id' => 2,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Janno Crisostomo',
            'email' => 'janno@egmail.com',
            'password' => bcrypt('12345678'),
            'role_id' => $socialWorkerRole->id,
            'gender_id' => $gender->id,
            'access_id' => 2,
            'email_verified_at' => now(),
        ]);
    }
}