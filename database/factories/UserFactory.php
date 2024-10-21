<?php

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Role;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Assign a role to the user.
     *
     * @param RoleEnum $role
     * @return $this
     */
    public function withRole(RoleEnum $role): static
    {
        return $this->afterCreating(function ($user) use ($role) {
            $roleModel = Role::firstOrCreate(['name' => $role]);
            $user->roles()->attach($roleModel);
        });
    }

    /**
     * Assign the admin role to the user.
     *
     * @return $this
     */
    public function asAdmin(): static
    {
        return $this->withRole(RoleEnum::ADMIN);
    }

    /**
     * Assign the user role to the user.
     *
     * @return $this
     */
    public function asUser(): static
    {
        return $this->withRole(RoleEnum::USER);
    }
}
