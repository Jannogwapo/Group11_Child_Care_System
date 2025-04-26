<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;
use App\Models\Gender;
use App\Models\Cases;
use App\Models\Status;
use App\Models\IsAStudent;
use App\Models\IsAPwd;
use App\Models\User;
use App\Models\Location;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'clientLastName' => $this->faker->lastName,
            'clientFirstName' => $this->faker->firstName,
            'clientMiddleName' => $this->faker->lastName,
            'clientBirthdate' => $this->faker->date(),
            'clientAge' => $this->faker->numberBetween(1, 18),
            'clientgender' => Gender::inRandomOrder()->first()->id,
            'clientaddress' => $this->faker->address,
            'clientguardian' => $this->faker->name,
            'clientguardianrelationship' => $this->faker->randomElement(['Mother', 'Father', 'Guardian']),
            'guardianphonenumber' => $this->faker->phoneNumber,
            'case_id' => Cases::inRandomOrder()->first()->id,
            'clientdateofadmission' => $this->faker->date(),
            'status_id' => Status::inRandomOrder()->first()->id,
            'isAStudent' => IsAStudent::inRandomOrder()->first()->id,
            'isAPwd' => IsAPwd::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'location_id' => Location::inRandomOrder()->first()->id,
        ];
    }
} 