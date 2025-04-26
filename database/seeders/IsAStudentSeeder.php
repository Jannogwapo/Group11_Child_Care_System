<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\IsAStudent;
use Illuminate\Support\Facades\DB;

class IsAStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Yes', 'No'];

        foreach ($statuses as $status) {
            DB::table('isAStudent')->updateOrInsert(
                ['status' => $status],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
