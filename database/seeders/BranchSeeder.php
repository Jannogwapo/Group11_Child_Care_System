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
            ],
            [
                'branchName' => 'Branch 55',
                'judgeName' => 'Judge Alice Williams'
            ],
            [
                'branchName' => 'Branch 70',
                'judgeName' => 'Judge Bob Brown'
            ],
            [
                'branchName' => 'Branch 15',
                'judgeName' => 'Judge Carol Davis'
            ],
            [
                'branchName' => 'Branch 11',
                'judgeName' => 'Judge David Miller'
            ],
            [
                'branchName' => 'Branch 88',
                'judgeName' => 'Judge Eva Green'
            ],
            [
                'branchName' => 'Branch 111',
                'judgeName' => 'Judge Frank White'
            ],
            [
                'branchName' => 'Branch 35',
                'judgeName' => 'Judge Grace Black'
            ]
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
