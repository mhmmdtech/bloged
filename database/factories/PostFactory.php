<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models\Category;
use App\Models\User;
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
        $authors = collect(User::all()->modelKeys());
        $categories = collect(Category::all()->modelKeys());

        return [
            'thumbnail' => ['directory' => 'images/thumbnails', 'defaultSize' => 'medium', 'sizes' => ['small' => fake()->imageUrl(640, 640), 'medium' => fake()->imageUrl(1280, 720), 'large' => fake()->imageUrl(1920, 1080)]],
            'title' => $title,
            'seo_title' => $title,
            'description' => $description,
            'seo_description' => $description,
            'body' => fake()->paragraphs(3, true),
            'reading_time' => fake()->randomDigitNotNull(),
            'status' => fake()->randomElement([PostStatus::Draft, PostStatus::Published, PostStatus::Archived]),
            'author_id' => $authors->random(),
            'category_id' => $categories->random(),
        ];
    }
}