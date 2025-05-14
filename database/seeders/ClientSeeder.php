<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $caseTypes = [
            'CICL', 'VAW C', 'SA', 'CAR', 'ABANDONED', 'ESCAPED', 'DISCHARGED'
        ];
        $statuses = ['NEW', 'RE-OPENED', 'ACTIVE', 'INACTIVE'];
        $genders = ['Male', 'Female'];

        $clients = [];
        foreach ($caseTypes as $caseType) {
            for ($i = 1; $i <= 10; $i++) {
                $clients[] = [
                    'clientFirstName' => Str::random(6),
                    'clientLastName' => Str::random(7),
                    'gender' => $genders[array_rand($genders)],
                    'address' => fake()->address(),
                    'contact' => fake()->phoneNumber(),
                    'status' => $statuses[array_rand($statuses)],
                    'admission_date' => Carbon::now()->subDays(rand(1, 3650)),
                    'case_type' => $caseType,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('clients')->insert($clients);
    }
}
