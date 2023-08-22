<?php

namespace Database\Factories;

use App\Enums\ProvinceStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Province>
 */
class ProvinceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->state();
        $creators = collect(User::all()->modelKeys());

        return [
            'local_name' => $name,
            'latin_name' => $name,
            'creator_id' => $creators->random(),
            'status' => fake()->randomElement([ProvinceStatus::Active, ProvinceStatus::Disable]),
        ];
    }
}