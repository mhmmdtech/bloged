<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['content manager', 'marketing manager', 'master'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $contentManager = Role::whereName('content manager')->first();
        $contentManager->syncPermissions([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);

        $marketingManager = Role::whereName('marketing manager')->first();
        $marketingManager->syncPermissions([31]);

        $master = Role::whereName('master')->first();
        $master->syncPermissions([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31]);

        User::findOrFail(1)->assignRole('master');

    }
}