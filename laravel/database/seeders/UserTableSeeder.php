<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Super Admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole(UserRole::ADMIN->value);

        // Moderator
        $moderator = User::factory()->create([
            'name' => 'Moderator',
            'email' => 'moderator@example.com',
        ]);
        $moderator->assignRole(UserRole::MODERATOR->value);

        // User
        $user = User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
        ]);
        $user->assignRole(UserRole::USER->value);

        // Tennis Players
        User::factory(6)->create()->each(function ($player): void {
            // Set dynamic name and email
            $player->name = fake()->name;
            $player->email = fake()->unique()->safeEmail;

            // Save the player with dynamic data
            $player->save();

            // Assign role to each player
            $player->assignRole(UserRole::USER->value);
        });
    }
}
