<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence();
        $description = fake()->text();

        return [
            'thumbnail' => fake()->imageUrl(),
            'title' => $title,
            'seo_title' => $title,
            'description' => $description,
            'seo_description' => $description,
            'body' => fake()->paragraphs(3, true),
            'status' => fake()->randomElement([PostStatus::Draft, PostStatus::Published, PostStatus::Archived]),
        ];
    }
}