<?php

namespace Database\Factories;

use App\Enums\CategoryStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->word();
        $description = fake()->text();
        $creators = collect(User::all()->modelKeys());

        return [
            'thumbnail' => fake()->imageUrl(),
            'title' => $title,
            'seo_title' => $title,
            'description' => $description,
            'seo_description' => $description,
            'status' => fake()->randomElement([CategoryStatus::Active, CategoryStatus::Disable]),
            'creator_id' => $creators->random(),
        ];
    }
}