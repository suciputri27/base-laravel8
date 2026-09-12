<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class SettingMenuSeeder extends Seeder
{
    public function run()
    {
        $existing = Menu::where('route_or_url', 'profiledinas.index')->first();

        if ($existing) {
            $existing->update([
                'name' => 'Pengaturan',
                'icon' => 'fas fa-cog',
                'route_or_url' => 'setting.index',
                'permission_name' => null,
                'order_no' => 4,
                'parent_id' => null,
                'is_active' => true,
            ]);

            return;
        }

        Menu::firstOrCreate(
            ['route_or_url' => 'setting.index'],
            [
                'name' => 'Pengaturan',
                'icon' => 'fas fa-cog',
                'permission_name' => null,
                'order_no' => 4,
                'parent_id' => null,
                'is_active' => true,
            ]
        );
    }
}
