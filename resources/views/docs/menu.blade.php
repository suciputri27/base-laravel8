@extends('layouts.app')

@section('title', 'Penambahan Menu')
@section('page-title', 'Penambahan Menu')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Cara Menambah Menu Sidebar</h5></div>
        <div class="card-body">
            <p>Menu sidebar dikelola pada halaman Menu Management. Setiap menu punya kolom berikut.</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Kolom</th>
                        <th>Fungsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td>name</td><td>Judul menu yang tampil di sidebar.</td></tr>
                    <tr><td>icon</td><td>Kelas icon Font Awesome, contoh fas fa-home.</td></tr>
                    <tr><td>route_or_url</td><td>Nama route Laravel atau URL lengkap.</td></tr>
                    <tr><td>permission_name</td><td>Nama permission yang dibutuhkan untuk melihat menu. Kosongkan agar semua user melihat.</td></tr>
                    <tr><td>order_no</td><td>Urutan tampilan menu.</td></tr>
                    <tr><td>parent_id</td><td>Parent menu untuk membuat submenu.</td></tr>
                    <tr><td>is_active</td><td>Status aktif atau nonaktif.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Menambah Menu Lewat Halaman Admin</h5></div>
        <div class="card-body">
            <ol>
                <li>Buka menu Menu Management pada sidebar.</li>
                <li>Klik tombol Tambah.</li>
                <li>Isi nama, icon, route, permission, urutan, parent, dan status.</li>
                <li>Klik Simpan.</li>
                <li>Muat ulang halaman untuk melihat menu baru di sidebar.</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Menambah Menu Lewat Seeder</h5></div>
        <div class="card-body">
            <p>Contoh menambah menu lewat seeder.</p>

            <pre><code>use App\Models\Menu;

$parent = Menu::firstOrCreate(
    ['name' => 'Laporan', 'parent_id' => null],
    [
        'icon' => 'fas fa-chart-bar',
        'route_or_url' => null,
        'permission_name' => null,
        'order_no' => 40,
        'is_active' => true,
    ]
);

Menu::firstOrCreate(
    ['route_or_url' => 'laporan.penjualan', 'parent_id' => $parent->id],
    [
        'name' => 'Penjualan',
        'icon' => 'fas fa-money-bill',
        'permission_name' => 'laporan.view',
        'order_no' => 1,
        'is_active' => true,
    ]
);</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Cara Kerja Penyaringan Menu</h5></div>
        <div class="card-body">
            <p>SidebarComposer memuat menu aktif lalu menyaring berdasarkan permission user. Super Admin otomatis melihat semua menu. Menu dengan permission kosong terlihat oleh semua user.</p>
        </div>
    </div>
@endsection
