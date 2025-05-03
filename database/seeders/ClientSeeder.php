<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\Cases;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $cases = Cases::all();
        foreach ($cases as $case) {
            Client::factory()->count(10)->create([
                'case_id' => $case->id,
            ]);
        }
    }
} 