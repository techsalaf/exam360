<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Create Core Roles
        $roles = [
            'Super Admin',
            'Admin',
            'Instructor',
            'Student'
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Create Permissions (Optional: Add your specific permissions here)
        $permissions = [
            'view_exams', 'create_exams', 'edit_exams', 'delete_exams',
            'view_users', 'create_users', 'edit_users', 'delete_users',
            'access_settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // 3. Assign Permissions to Roles (Example)
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
        }
    }
}