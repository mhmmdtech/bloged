<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeds = [
            'local' => [
                UserSeeder::class,
                CategorySeeder::class,
                PostSeeder::class,
                ProvinceSeeder::class,
                CitySeeder::class,
                PermissionSeeder::class,
                RoleSeeder::class
            ],
            'production' => [
                UserSeeder::class,
                PermissionSeeder::class,
                RoleSeeder::class
            ],
        ][app()->environment()];

        $this->call($seeds);
    }
}