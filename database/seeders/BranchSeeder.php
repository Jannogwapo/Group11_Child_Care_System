<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'branchName' => 'Branch 1',
                'judgeName' => 'Judge John Doe'
            ],
            [
                'branchName' => 'Branch 2',
                'judgeName' => 'Judge Jane Smith'
            ],
            [
                'branchName' => 'Branch 3',
                'judgeName' => 'Judge Robert Johnson'
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
