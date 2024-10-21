<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Enums\RoleEnum as RoleEnum;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (RoleEnum::cases() as $roleEnum) {
            Role::updateOrCreate(
                ['name' => $roleEnum->value],
                ['name' => $roleEnum->value]
            );
        }
    }
}
