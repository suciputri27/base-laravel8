<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Menu::create([
            'name' => 'Dashboard',
            'icon' => 'fas fa-home',
            'route_or_url' => 'dashboard',
            'permission_name' => 'dashboard.view',
            'order_no' => 1,
            'parent_id' => null,
            'is_active' => true,
        ]);

        $masterData = Menu::create([
            'name' => 'Master Data',
            'icon' => 'fas fa-database',
            'route_or_url' => null,
            'permission_name' => null,
            'order_no' => 2,
            'parent_id' => null,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'User Management',
            'icon' => 'fas fa-users',
            'route_or_url' => 'users.index',
            'permission_name' => 'users.view',
            'order_no' => 1,
            'parent_id' => $masterData->id,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'Menu Management',
            'icon' => 'fas fa-bars',
            'route_or_url' => 'menus.index',
            'permission_name' => 'menus.view',
            'order_no' => 2,
            'parent_id' => $masterData->id,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'Role Management',
            'icon' => 'fas fa-user-shield',
            'route_or_url' => 'roles.index',
            'permission_name' => 'roles.view',
            'order_no' => 3,
            'parent_id' => $masterData->id,
            'is_active' => true,
        ]);

        Menu::create([
            'name' => 'Permission Management',
            'icon' => 'fas fa-key',
            'route_or_url' => 'permissions.index',
            'permission_name' => 'permissions.view',
            'order_no' => 4,
            'parent_id' => $masterData->id,
            'is_active' => true,
        ]);
    }
}
