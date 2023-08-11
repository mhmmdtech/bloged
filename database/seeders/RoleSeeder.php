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
        $roles = ['user', 'content manager', 'master'];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        $user = Role::whereName('user')->first();
        $user->syncPermissions([16, 17, 18]);

        $contentManager = Role::whereName('content manager')->first();
        $contentManager->syncPermissions([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 16, 17, 18]);

        $master = Role::whereName('master')->first();
        $master->syncPermissions([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18]);

        User::findOrFail(1)->assignRole('master');

    }
}