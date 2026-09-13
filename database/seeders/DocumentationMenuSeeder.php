<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class DocumentationMenuSeeder extends Seeder
{
    public function run()
    {
        $documentation = Menu::firstOrCreate(
            ['name' => 'Dokumentasi', 'parent_id' => null],
            [
                'icon' => 'fas fa-book',
                'route_or_url' => null,
                'permission_name' => null,
                'order_no' => 30,
                'is_active' => true,
            ]
        );

        $items = [
            ['name' => 'Arsitektur dan Instalasi', 'icon' => 'fas fa-sitemap', 'route_or_url' => 'docs.arsitektur', 'order_no' => 1],
            ['name' => 'Pembuatan CRUD', 'icon' => 'fas fa-code', 'route_or_url' => 'docs.crud', 'order_no' => 2],
            ['name' => 'Penambahan Menu', 'icon' => 'fas fa-bars', 'route_or_url' => 'docs.menu', 'order_no' => 3],
            ['name' => 'Helper dan Library', 'icon' => 'fas fa-tools', 'route_or_url' => 'docs.helper', 'order_no' => 4],
            ['name' => 'Infinite Scroll AJAX', 'icon' => 'fas fa-sync-alt', 'route_or_url' => 'docs.ajax', 'order_no' => 5],
            ['name' => 'RBAC dan Permission', 'icon' => 'fas fa-user-shield', 'route_or_url' => 'docs.rbac', 'order_no' => 6],
            ['name' => 'Modul Kategori dan Berita', 'icon' => 'fas fa-newspaper', 'route_or_url' => 'docs.modul-kategori-berita', 'order_no' => 7],
            ['name' => 'Panduan Module Baru', 'icon' => 'fas fa-map', 'route_or_url' => 'docs.panduan-module', 'order_no' => 8],
            ['name' => 'Soft Delete', 'icon' => 'fas fa-trash-restore', 'route_or_url' => 'docs.softdelete', 'order_no' => 9],
        ];

        foreach ($items as $item) {
            Menu::firstOrCreate(
                ['route_or_url' => $item['route_or_url'], 'parent_id' => $documentation->id],
                [
                    'name' => $item['name'],
                    'icon' => $item['icon'],
                    'permission_name' => null,
                    'order_no' => $item['order_no'],
                    'is_active' => true,
                ]
            );
        }
    }
}
