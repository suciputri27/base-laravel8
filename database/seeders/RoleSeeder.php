<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $superAdmin = Role::findOrCreate('Super Admin', 'web');
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::findOrCreate('Admin', 'web');
        $admin->syncPermissions([
            'dashboard.view',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'menus.view',
            'menus.create',
            'menus.edit',
            'menus.delete',
        ]);
    }
}
