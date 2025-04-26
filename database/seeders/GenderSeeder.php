<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Gender;

class GenderSeeder extends Seeder
{
    public function run()
    {
        $genders = ['Male', 'Female'];

        foreach ($genders as $gender) {
            Gender::firstOrCreate(['gender_name' => $gender]); // Use 'gender_name' as the column
        }
    }
}