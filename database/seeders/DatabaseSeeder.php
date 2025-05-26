<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Disable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Seed basic tables first
            $this->call([
                GenderSeeder::class,
                CasesSeeder::class,
                StatusSeeder::class,
                IsAStudentSeeder::class,
                IsAPwdSeeder::class,
                UserRoleSeeder::class,
                AccessLogSeeder::class,
                UserSeeder::class,
                LocationSeeder::class,
                ClientSeeder::class,
                BranchSeeder::class,
                
                
            ]);

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        } catch (\Exception $e) {
            Log::error('Database seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
