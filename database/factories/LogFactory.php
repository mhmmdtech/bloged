<?php

namespace Database\Factories;

use App\Models;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Log>
 */
class LogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actioners = collect(Models\User::all()->modelKeys());
        $model = Models\User::inRandomOrder()->first();

        return [
            'actioner_id' => $actioners->random(),
            'action' => fake()->randomElement(['create', 'update', 'delete']),
            'model_type' => User::class,
            'model_id' => $model->id,
            'old_model' => $model->toArray(),
            'new_model' => $model->toArray()
        ];
    }
}