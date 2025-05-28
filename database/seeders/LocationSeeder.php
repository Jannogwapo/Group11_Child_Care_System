<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create some default locations
        $locations = [
            'IN-HOUSE',
            'ESCAPED',
            'DISCHARGED',
            'TRANSFER',
        ];

        foreach ($locations as $location) {
            DB::table('location')->insert([
                'location' => $location,
            ]);
        }
    }
}
