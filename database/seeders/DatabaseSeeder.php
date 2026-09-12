<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(DocumentationMenuSeeder::class);
        $this->call(ContentSeeder::class);
        $this->call(SettingMenuSeeder::class);

        $superAdmin = User::firstOrCreate(
            ['email' => 'admin@baseapp.test'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );
        $superAdmin->assignRole('Super Admin');

        $admin = User::firstOrCreate(
            ['email' => 'admin2@baseapp.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
            ]
        );
        $admin->assignRole('Admin');
    }
}
