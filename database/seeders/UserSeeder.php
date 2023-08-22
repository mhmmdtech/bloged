<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        match (app()->environment()) {
            'local' => User::factory()->count(5)->create(),
            'testing' => User::factory()->count(10)->create(),
            'production' => User::factory()->create(['username' => 'temporary']),
            default => exit ,
        };
    }
}