<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Enums\RoleEnum as RoleEnum;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminadmin321'),
        ]);

        // Attach admin role to admin user
        $adminRole = Role::where('name', RoleEnum::ADMIN)->first();
        $adminUser->roles()->attach($adminRole);

        // Create regular users
        User::factory(9)->create()->each(function ($user) {
            $userRole = Role::where('name', RoleEnum::USER)->first();
            $user->roles()->attach($userRole);
        });
    }
}
