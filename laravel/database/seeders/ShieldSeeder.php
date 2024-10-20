<?php

namespace Database\Seeders;

use BezhanSalleh\FilamentShield\Support\Utils;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;
use Str;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = config('shield-permissions.roles');
        $directPermissions = config('shield-permissions.direct_permissions');

        // Process each role and its permissions
        foreach ($rolesWithPermissions as $roleName => $roleData) {
            $guardName = $roleData['guard_name'];

            // Create or find the role
            $roleModel = Utils::getRoleModel();
            $role = $roleModel::firstOrCreate([
                'name' => $roleName,
                'guard_name' => $guardName,
            ]);

            // Assign resource permissions
            if (isset($roleData['permissions']['resource'])) {
                static::createResourcePermissions($roleData['permissions']['resource'], $role, $guardName);
            }

            // Assign special permissions
            if (isset($roleData['permissions']['special'])) {
                static::createSpecialPermissions($roleData['permissions']['special'], $role, $guardName);
            }

            // Assign page permissions
            if (isset($roleData['permissions']['page'])) {
                static::createPagePermissions($roleData['permissions']['page'], $role, $guardName);
            }
        }

        // Create any direct permissions that are not tied to a role
        static::createDirectPermissions($directPermissions);
    }

    protected static function createResourcePermissions(array $resources, $role, string $guardName): void
    {
        foreach ($resources as $resource => $actions) {
            $resourceName = str_replace('-', '::', Str::kebab($resource));

            foreach ($actions as $action) {
                $permissionName = "{$action}_{$resourceName}";

                // Create or find the permission
                $permissionModel = Utils::getPermissionModel();
                $permission = $permissionModel::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $guardName,
                ]);

                // Assign permission to role
                $role->givePermissionTo($permission);
            }
        }
    }

    protected static function createSpecialPermissions(array $specialPermissions, $role, string $guardName): void
    {
        foreach ($specialPermissions as $specialPermission) {
            // Create or find the special permission
            $permissionModel = Utils::getPermissionModel();
            $permission = $permissionModel::firstOrCreate([
                'name' => $specialPermission,
                'guard_name' => $guardName,
            ]);

            // Assign permission to role
            $role->givePermissionTo($permission);
        }
    }

    protected static function createPagePermissions(array $pagePermissions, $role, string $guardName): void
    {
        foreach ($pagePermissions as $pagePermission) {
            // Add "page_" prefix to page permissions
            $permissionName = "page_{$pagePermission}";

            // Create or find the page permission
            $permissionModel = Utils::getPermissionModel();
            $permission = $permissionModel::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guardName,
            ]);

            // Assign permission to role
            $role->givePermissionTo($permission);
        }
    }

    protected static function createDirectPermissions(array $directPermissions): void
    {
        foreach ($directPermissions as $permissionName => $guardName) {
            // Create or find the direct permission
            $permissionModel = Utils::getPermissionModel();
            $permissionModel::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guardName,
            ]);
        }
    }
}
