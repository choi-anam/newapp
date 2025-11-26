<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Buat Permissions dengan deskripsi supaya jelas fungsinya
        $permissions = [
            'view-dashboard' => 'Melihat halaman dashboard admin',

            // Role Management
            'create-role' => 'Membuat role baru',
            'read-role' => 'Melihat daftar role',
            'update-role' => 'Mengubah role',
            'delete-role' => 'Menghapus role',

            // Permission Management
            'create-permission' => 'Membuat permission baru',
            'read-permission' => 'Melihat daftar permission',
            'update-permission' => 'Mengubah permission',
            'delete-permission' => 'Menghapus permission',

            // User Management
            'create-user' => 'Membuat user baru',
            'read-user' => 'Melihat daftar user',
            'update-user' => 'Mengubah user',
            'delete-user' => 'Menghapus user',
        ];

        foreach ($permissions as $name => $description) {
            // Use model instance to avoid mass-assignment issues
            $perm = Permission::where('name', $name)->first();
            if (! $perm) {
                $perm = new Permission();
                $perm->name = $name;
                $perm->guard_name = config('auth.defaults.guard', 'web');
                $perm->description = $description;
                $perm->save();
            } else {
                // update description if changed
                $perm->description = $description;
                $perm->guard_name = config('auth.defaults.guard', 'web');
                $perm->save();
            }
        }

        // Buat Roles dan assign permissions
        $superAdminPermissions = Permission::all();

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $superAdmin->syncPermissions($superAdminPermissions);

        $adminPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'create-role',
            'read-role',
            'update-role',
            'delete-role',
            'create-permission',
            'read-permission',
            'update-permission',
            'delete-permission',
            'create-user',
            'read-user',
            'update-user',
            'delete-user',
        ])->get();

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($adminPermissions);

        $editorPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'read-user',
        ])->get();

        $editor = Role::firstOrCreate(['name' => 'editor']);
        $editor->syncPermissions($editorPermissions);

        $userPermissions = Permission::whereIn('name', [
            'view-dashboard',
        ])->get();

        $user = Role::firstOrCreate(['name' => 'user']);
        $user->syncPermissions($userPermissions);
    }
}
