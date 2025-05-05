<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccessLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed the access_logs table with sample data
        DB::table('access_logs')->insert([
            [
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                
                'status' => 'accept',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                
                'status' => 'denied',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
