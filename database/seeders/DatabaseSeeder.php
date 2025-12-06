<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Create test user only if not exists
        $superAdmin = \App\Models\User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $editor = \App\Models\User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'editor',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'user',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );

        $user2 = \App\Models\User::firstOrCreate(
            ['email' => 'user2@example.com'],
            [
                'name' => 'user2',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        // Seed roles and permissions
        $this->call(\Database\Seeders\RolePermissionSeeder::class);

        $superAdmin->assignRole('super-admin');
        $admin->assignRole('admin');
        $editor->assignRole('editor');
        $user->assignRole('user');
        $user2->assignRole('user');
    }
}
