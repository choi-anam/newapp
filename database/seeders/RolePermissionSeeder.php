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
            'view roles' => 'Melihat daftar role',
            'create roles' => 'Membuat role baru',
            'update roles' => 'Mengubah role',
            'delete roles' => 'Menghapus role',

            // Permission Management
            'view permissions' => 'Melihat daftar permission',
            'create permissions' => 'Membuat permission baru',
            'update permissions' => 'Mengubah permission',
            'delete permissions' => 'Menghapus permission',

            // User Management
            'view users' => 'Melihat daftar user',
            'create users' => 'Membuat user baru',
            'update users' => 'Mengubah user',
            'delete users' => 'Menghapus user',
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
            'view roles',
            'view permissions',
            'view users',
        ])->get();

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions($adminPermissions);

        $editorPermissions = Permission::whereIn('name', [
            'view-dashboard',
            'view users',
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
