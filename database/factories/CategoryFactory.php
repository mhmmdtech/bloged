<?php

namespace Database\Factories;

use App\Enums\CategoryStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'thumbnail' => ['directory' => 'images/thumbnails', 'defaultSize' => 'medium', 'sizes' => ['small' => fake()->imageUrl(640, 640), 'medium' => fake()->imageUrl(1280, 720), 'large' => fake()->imageUrl(1920, 1080)]],
            'title' => $title,
            'seo_title' => $title,
            'description' => $description,
            'seo_description' => $description,
            'slug' => Str::slug($title),
            'status' => fake()->randomElement([CategoryStatus::Active, CategoryStatus::Disable]),
            'creator_id' => $creators->random(),
        ];
    }
}