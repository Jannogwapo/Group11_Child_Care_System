<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'OLD',
            'NEW',
            'RE-OPENED',
        ];

        foreach ($statuses as $status) {
            DB::table('status')->updateOrInsert(
                ['status_name' => $status],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
