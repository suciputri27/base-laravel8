@extends('layouts.app')

@section('title', 'Arsitektur dan Instalasi')
@section('page-title', 'Arsitektur dan Instalasi')

@section('content')
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Alur Arsitektur</h5></div>
        <div class="card-body">
            <p>Base app memakai Clean Architecture dan Repository Pattern. Setiap lapisan punya tugas yang jelas.</p>

            <table class="table">
                <thead>
                    <tr>
                        <th>Lapisan</th>
                        <th>Lokasi</th>
                        <th>Tanggung Jawab</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Entity atau Model</td>
                        <td>app/Models</td>
                        <td>Mewakili tabel database dan relasi Eloquent.</td>
                    </tr>
                    <tr>
                        <td>Repository Contract</td>
                        <td>app/Repositories/Contracts</td>
                        <td>Kontrak interface yang dipakai Service.</td>
                    </tr>
                    <tr>
                        <td>Repository Eloquent</td>
                        <td>app/Repositories/Eloquent</td>
                        <td>Implementasi kueri database.</td>
                    </tr>
                    <tr>
                        <td>Service</td>
                        <td>app/Services</td>
                        <td>Business logic, transaksi, dan pemanggilan repository.</td>
                    </tr>
                    <tr>
                        <td>Controller</td>
                        <td>app/Http/Controllers</td>
                        <td>Menerima request, memanggil service, mengembalikan response.</td>
                    </tr>
                    <tr>
                        <td>FormRequest</td>
                        <td>app/Http/Requests</td>
                        <td>Validasi input dari client.</td>
                    </tr>
                </tbody>
            </table>

            <p>Alur request berjalan dari Route, Controller, Service, Repository, lalu kembali menjadi JSON atau view.</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Struktur Folder Utama</h5></div>
        <div class="card-body">
            <pre><code>app/
  Helpers/
  Http/
    Controllers/
    Middleware/
    Requests/
  Models/
  Repositories/
    Contracts/
    Eloquent/
  Services/
    Contracts/
  Support/
  Traits/
  View/
    Composers/</code></pre>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h5 class="mb-0">Instalasi</h5></div>
        <div class="card-body">
            <p>Pada environment baru jalankan perintah berikut.</p>

            <pre><code>composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve</code></pre>

            <p>Akun default yang dibuat seeder:</p>
            <ul>
                <li>Super Admin, email admin@baseapp.test, password password</li>
                <li>Admin, email admin2@baseapp.test, password password</li>
            </ul>

            <p>Aset JavaScript dan CSS sudah tersedia di public/js dan public/css, sehingga tidak wajib menjalankan npm build.</p>
        </div>
    </div>
@endsection
