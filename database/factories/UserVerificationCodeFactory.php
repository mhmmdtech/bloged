<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserVerificationCode>
 */
class UserVerificationCodeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $users = collect(User::all()->modelKeys());

        return [
            'token' => generateRandomCode(5, 8),
            'user_id' => $users->random(),
            'expires_at' => now()->addHour(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function nonExpirable(): static
    {
        return $this->state(fn(array $attributes) => [
            'expires_at' => null,
        ]);
    }
}