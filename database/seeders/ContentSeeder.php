<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run()
    {
        $content = Menu::firstOrCreate(
            ['name' => 'Konten', 'parent_id' => null],
            [
                'icon' => 'fas fa-folder-open',
                'route_or_url' => null,
                'permission_name' => null,
                'order_no' => 5,
                'is_active' => true,
            ]
        );

        Menu::firstOrCreate(
            ['route_or_url' => 'categories.index', 'parent_id' => $content->id],
            [
                'name' => 'Kategori',
                'icon' => 'fas fa-tags',
                'permission_name' => null,
                'order_no' => 1,
                'is_active' => true,
            ]
        );

        Menu::firstOrCreate(
            ['route_or_url' => 'posts.index', 'parent_id' => $content->id],
            [
                'name' => 'Berita',
                'icon' => 'fas fa-newspaper',
                'permission_name' => null,
                'order_no' => 2,
                'is_active' => true,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'teknologi'],
            [
                'name' => 'Teknologi',
                'description' => 'Berita seputar teknologi dan inovasi.',
                'is_active' => true,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'bisnis'],
            [
                'name' => 'Bisnis',
                'description' => 'Berita seputar bisnis dan ekonomi.',
                'is_active' => true,
            ]
        );

        Category::firstOrCreate(
            ['slug' => 'hiburan'],
            [
                'name' => 'Hiburan',
                'description' => 'Berita seputar hiburan dan gaya hidup.',
                'is_active' => true,
            ]
        );
    }
}
