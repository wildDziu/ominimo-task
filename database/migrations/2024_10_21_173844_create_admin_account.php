<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role as RoleModel;

return new class extends Migration
{
    public function up(): void
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('adminadmin321'),
        ]);

        // Attach admin role to admin user
        $adminRole = RoleModel::where('name', RoleEnum::ADMIN)->first();
        $adminUser->roles()->attach($adminRole);
    }

    public function down(): void
    {
        // Remove the admin user
        User::where('email', 'admin@example.com')->delete();
    }
};
