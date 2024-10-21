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
        User::factory(10)->create()->each(function ($user) {
            $userRole = Role::where('name', RoleEnum::USER)->first();
            $user->roles()->attach($userRole);
        });
    }
}
