<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PhilippineProvince;
use App\Models\PhilippineCity;
use App\Models\PhilippineBarangay;

class PhilippineAddressSeeder extends Seeder
{
    public function run(): void
    {
        // Sample data for Metro Manila
        $metroManila = PhilippineProvince::create([
            'name' => 'Metro Manila',
            'code' => 'MM'
        ]);

        // Cities in Metro Manila
        $cities = [
            'Manila' => [
                'Barangay 1', 'Barangay 2', 'Barangay 3', 'Barangay 4', 'Barangay 5',
                'Barangay 6', 'Barangay 7', 'Barangay 8', 'Barangay 9', 'Barangay 10'
            ],
            'Quezon City' => [
                'Alicia', 'Bagong Silangan', 'Batasan Hills', 'Commonwealth', 'Culiat',
                'E. Rodriguez', 'Holy Spirit', 'Immaculate Concepcion', 'Kamuning', 'Katipunan'
            ],
            'Makati' => [
                'Bangkal', 'Bel-Air', 'Carmona', 'Cembo', 'Comembo',
                'Dasmariñas', 'East Rembo', 'Forbes Park', 'Guadalupe Nuevo', 'Guadalupe Viejo'
            ],
            'Pasig' => [
                'Bagong Ilog', 'Bambang', 'Buting', 'Caniogan', 'Dela Paz',
                'Kalawaan', 'Kapitolyo', 'Malinao', 'Manggahan', 'Maybunga'
            ],
            'Taguig' => [
                'Bagumbayan', 'Bambang', 'Calzada', 'Central Bicutan', 'Fort Bonifacio',
                'Hagonoy', 'Ibayo-Tipas', 'Katuparan', 'Ligid-Tipas', 'Lower Bicutan'
            ]
        ];

        foreach ($cities as $cityName => $barangays) {
            $cityCode = strtoupper(substr($cityName, 0, 3));
            $city = PhilippineCity::create([
                'name' => $cityName,
                'code' => $cityCode,
                'province_id' => $metroManila->id
            ]);

            foreach ($barangays as $index => $barangayName) {
                $barangayCode = $cityCode . strtoupper(substr($barangayName, 0, 2)) . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                PhilippineBarangay::create([
                    'name' => $barangayName,
                    'code' => $barangayCode,
                    'city_id' => $city->id
                ]);
            }
        }

        // Add more provinces and their cities/barangays as needed
        $provinces = [
            'Cebu' => [
                'Cebu City' => ['Adlaon', 'Agsungot', 'Apas', 'Babag', 'Bacayan'],
                'Mandaue City' => ['Alang-alang', 'Bakilid', 'Banilad', 'Basak', 'Cabancalan'],
                'Lapu-Lapu City' => ['Agus', 'Babag', 'Bankal', 'Basak', 'Buaya']
            ],
            'Davao' => [
                'Davao City' => ['1-A', '2-A', '3-A', '4-A', '5-A'],
                'Digos City' => ['Aplaya', 'Binaton', 'Colorado', 'Dulangan', 'Goma'],
                'Tagum City' => ['Apokon', 'Bincungan', 'Busaon', 'Canocotan', 'Cuambogan']
            ]
        ];

        foreach ($provinces as $provinceName => $cities) {
            $province = PhilippineProvince::create([
                'name' => $provinceName,
                'code' => strtoupper(substr($provinceName, 0, 3))
            ]);

            foreach ($cities as $cityName => $barangays) {
                $cityCode = strtoupper(substr($cityName, 0, 3));
                $city = PhilippineCity::create([
                    'name' => $cityName,
                    'code' => $cityCode,
                    'province_id' => $province->id
                ]);

                foreach ($barangays as $index => $barangayName) {
                    $barangayCode = $cityCode . strtoupper(substr($barangayName, 0, 2)) . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                    PhilippineBarangay::create([
                        'name' => $barangayName,
                        'code' => $barangayCode,
                        'city_id' => $city->id
                    ]);
                }
            }
        }
    }
} 