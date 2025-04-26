<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\IsAPwd;
use Illuminate\Support\Facades\DB;


class IsAPwdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = ['Yes', 'No'];

        foreach ($statuses as $status) {
            DB::table('isAPwd')->updateOrInsert(
                ['status' => $status],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
