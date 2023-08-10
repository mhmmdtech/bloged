<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [];
        $operations = ['browse', 'read', 'edit', 'update', 'delete'];
        $sections = ['category', 'post', 'user'];

        foreach ($sections as $section) {
            foreach ($operations as $operation) {
                array_push($permissions, "{$operation} {$section}");
            }
        }

        array_push($permissions, 'browse profile', 'edit profile', 'delete profile');


        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}