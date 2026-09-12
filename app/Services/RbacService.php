<?php

namespace App\Services;

use App\Helpers\CursorPaginationHelper;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RbacService
{
    public function paginateRoles(array $options = []): array
    {
        $result = CursorPaginationHelper::paginate(Role::query()->with('permissions')->where('guard_name', 'web'), $options);

        $result['data'] = $result['data']->map(function ($role) {
            return [
                'encrypted_id' => id_encode((int) $role->id),
                'name' => $role->name,
                'guard_name' => $role->guard_name,
                'permissions' => $role->permissions->pluck('name')->values(),
                'created_at' => $role->created_at ? $role->created_at->format('Y-m-d H:i:s') : null,
                'updated_at' => $role->updated_at ? $role->updated_at->format('Y-m-d H:i:s') : null,
            ];
        })->values();

        return $result;
    }

    public function getRoles(): Collection
    {
        return Role::where('guard_name', 'web')->orderBy('name')->get();
    }

    public function createRole(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);

        if (array_key_exists('permissions', $data)) {
            $role->syncPermissions($data['permissions'] ?? []);
        }

        return $role;
    }

    public function updateRole(int $id, array $data): Role
    {
        $role = Role::findById($id, 'web');
        $role->update(['name' => $data['name']]);

        if (array_key_exists('permissions', $data)) {
            $role->syncPermissions($data['permissions'] ?? []);
        }

        return $role;
    }

    public function deleteRole(int $id): bool
    {
        $role = Role::findById($id, 'web');
        $role->delete();

        return true;
    }

    public function paginatePermissions(array $options = []): array
    {
        $result = CursorPaginationHelper::paginate(Permission::query()->where('guard_name', 'web'), $options);

        $result['data'] = $result['data']->map(function ($permission) {
            return [
                'encrypted_id' => id_encode((int) $permission->id),
                'name' => $permission->name,
                'guard_name' => $permission->guard_name,
                'created_at' => $permission->created_at,
                'updated_at' => $permission->updated_at,
            ];
        })->values();

        return $result;
    }

    public function getPermissions(): Collection
    {
        return Permission::where('guard_name', 'web')->orderBy('name')->get();
    }

    public function createPermission(array $data): Permission
    {
        return Permission::create([
            'name' => $data['name'],
            'guard_name' => 'web',
        ]);
    }

    public function updatePermission(int $id, array $data): Permission
    {
        $permission = Permission::findById($id, 'web');
        $permission->update(['name' => $data['name']]);

        return $permission;
    }

    public function deletePermission(int $id): bool
    {
        $permission = Permission::findById($id, 'web');
        $permission->delete();

        return true;
    }
}
