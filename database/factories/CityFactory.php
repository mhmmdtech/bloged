<?php

namespace Database\Factories;

use App\Enums\CityStatus;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->state();
        $provinces = collect(Province::all()->modelKeys());
        $creators = collect(User::all()->modelKeys());

        return [
            'local_name' => $name,
            'latin_name' => $name,
            'province_id' => $provinces->random(),
            'creator_id' => $creators->random(),
            'status' => fake()->randomElement([CityStatus::Active, CityStatus::Disable]),
        ];
    }
}