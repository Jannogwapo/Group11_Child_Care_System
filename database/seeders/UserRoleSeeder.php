<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['Admin', 'Social Worker'];

        foreach ($roles as $role) {
            UserRole::firstOrCreate(['role_name' => $role]);
        }
    }
}