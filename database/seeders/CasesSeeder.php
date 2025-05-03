<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CasesSeeder extends Seeder
{
    public function run()
    {
        DB::table('case')->truncate();
        $cases = [
            ['case_name' => 'CICL'],
            ['case_name' => 'VAW C'],
            ['case_name' => 'SA'],
            ['case_name' => 'CAR'],
            ['case_name' => 'ABANDONED'],
            ['case_name' => 'ESCAPE'],
            ['case_name' => 'DISCHARGE'],
           ];

        foreach ($cases as $case) {
            DB::table('case')->insert($case);
        }
    }
}