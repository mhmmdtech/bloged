<?php

namespace Database\Factories;

use App\Enums\PostStatus;
use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $body = fake()->paragraphs(3, true);
        $authors = collect(Models\User::all()->modelKeys());
        $categories = collect(Models\Category::all()->modelKeys());

        return [
            'thumbnail' => ['directory' => 'images/thumbnails', 'defaultSize' => 'medium', 'sizes' => ['small' => fake()->imageUrl(640, 640), 'medium' => fake()->imageUrl(1280, 720), 'large' => fake()->imageUrl(1920, 1080)]],
            'title' => $title,
            'seo_title' => $title,
            'description' => $description,
            'seo_description' => $description,
            'unique_id' => fake()->unique()->randomNumber(9, true) . "01",
            'slug' => Str::slug($title),
            'body' => $body,
            'html_content' => $body,
            'reading_time' => fake()->randomDigitNotNull(),
            'status' => fake()->randomElement([PostStatus::Draft, PostStatus::Published, PostStatus::Archived]),
            'author_id' => $authors->random(),
            'category_id' => $categories->random(),
        ];
    }
}