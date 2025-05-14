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
                \Database\Seeders\GenderSeeder::class,
                \Database\Seeders\CasesSeeder::class,
                \Database\Seeders\StatusSeeder::class,
                \Database\Seeders\IsAStudentSeeder::class,
                \Database\Seeders\IsAPwdSeeder::class,
                \Database\Seeders\UserRoleSeeder::class,
                \Database\Seeders\AccessLogSeeder::class,
                \Database\Seeders\UserSeeder::class,
                LocationSeeder::class,
                \Database\Seeders\PhilippineAddressSeeder::class,
                \Database\Seeders\ClientSeeder::class,
            ]);

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        } catch (\Exception $e) {
            Log::error('Database seeding failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
