# Permission & Authorization Fix Summary

## Problem
Admin users dengan role 'admin' bisa membuat, mengubah, dan menghapus role/permission, padahal seharusnya hanya 'super-admin' yang memiliki hak tersebut. Admin seharusnya hanya bisa **melihat/view** saja.

## Root Cause
1. **RoleController dan PermissionController** tidak memiliki authorization checks
2. Middleware hanya cek apakah user adalah admin atau super-admin, tapi tidak cek specific permissions
3. **RolePermissionSeeder** memberikan semua permission (create, read, update, delete) ke admin role
4. Nama permission tidak konsisten antara seeder dan middleware expectations

## Solution Implemented

### 1. RoleController.php
Menambahkan permission middleware di constructor:
```php
public function __construct()
{
    $this->middleware('permission:view roles', ['only' => ['index', 'show']]);
    $this->middleware('permission:create roles', ['only' => ['create', 'store']]);
    $this->middleware('permission:update roles', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete roles', ['only' => ['destroy']]);
}
```

### 2. PermissionController.php
Menambahkan permission middleware di constructor:
```php
public function __construct()
{
    $this->middleware('permission:view permissions', ['only' => ['index', 'show']]);
    $this->middleware('permission:create permissions', ['only' => ['create', 'store']]);
    $this->middleware('permission:update permissions', ['only' => ['edit', 'update']]);
    $this->middleware('permission:delete permissions', ['only' => ['destroy']]);
}
```

### 3. RolePermissionSeeder.php
**Diubah nama permission untuk konsistensi:**
- Dari: `read-role`, `create-role`, `update-role`, `delete-role`
- Menjadi: `view roles`, `create roles`, `update roles`, `delete roles`
- Sama untuk permission management dan user management

**Diubah permission untuk admin role (HANYA VIEW):**
```php
$adminPermissions = Permission::whereIn('name', [
    'view-dashboard',
    'view roles',
    'view permissions', 
    'view users',
])->get();
```

**Super-admin tetap punya semua permissions**

## Role Permissions Matrix Setelah Fix

| Action | Super-Admin | Admin | Editor | User |
|--------|-----------|-------|--------|------|
| View Dashboard | ✅ | ✅ | ✅ | ❌ |
| View Roles | ✅ | ✅ | ❌ | ❌ |
| Create Roles | ✅ | ❌ | ❌ | ❌ |
| Update Roles | ✅ | ❌ | ❌ | ❌ |
| Delete Roles | ✅ | ❌ | ❌ | ❌ |
| View Permissions | ✅ | ✅ | ❌ | ❌ |
| Create Permissions | ✅ | ❌ | ❌ | ❌ |
| Update Permissions | ✅ | ❌ | ❌ | ❌ |
| Delete Permissions | ✅ | ❌ | ❌ | ❌ |
| View Users | ✅ | ✅ | ✅ | ❌ |
| Create Users | ✅ | ❌ | ❌ | ❌ |
| Update Users | ✅ | ❌ | ❌ | ❌ |
| Delete Users | ✅ | ❌ | ❌ | ❌ |

## How to Apply
1. Database sudah di-update dengan menjalankan: `php artisan db:seed --class=RolePermissionSeeder`
2. Jika ada user dengan role 'admin', mereka sekarang hanya bisa view role/permission/user
3. Untuk manage (create/update/delete) role/permission, user harus memiliki role 'super-admin'

## Testing
1. Login sebagai admin user
2. Navigasi ke `/admin/roles` - harusnya bisa lihat daftar
3. Coba klik "Tambah Role" atau "Edit" - seharusnya mendapat 403 Forbidden
4. Login sebagai super-admin user
5. Coba create/update/delete role - seharusnya berfungsi normal
