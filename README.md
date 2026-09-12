# Base App Laravel

Base project Laravel 8 dengan Clean Architecture, Repository Pattern, RBAC menggunakan Spatie Laravel Permission, serta helper enkripsi ID dan cursor pagination.

## Prasyarat

- PHP 7.3 atau 8.x dengan ekstensi MySQL
- Composer
- MySQL
- Node.js (opsional)

## Instalasi Awal

1. Clone atau copy project ini.

2. Install dependency PHP:

```bash
composer install
```

3. Buat file .env:

Windows:

```bash
copy .env.example .env
```

Linux atau Mac:

```bash
cp .env.example .env
```

4. Generate application key:

```bash
php artisan key:generate
```

5. Buka file .env, lalu sesuaikan konfigurasi database:

```env
DB_DATABASE=baseapp
DB_USERNAME=root
DB_PASSWORD=
```

Buat database tersebut di MySQL terlebih dahulu.

6. Jalankan migrasi dan seeder:

```bash
php artisan migrate --seed
```

Seeder akan membuat permission, role, menu, dan dua akun default.

7. Jalankan server:

```bash
php artisan serve
```

8. Buka http://127.0.0.1:8000

## Akun Default

- Super Admin: `admin@baseapp.test` dengan password `password`
- Admin: `admin2@baseapp.test` dengan password `password`

## Catatan

- Aset JavaScript dan CSS sudah dikompilasi di folder public/js dan public/css, sehingga tidak wajib menjalankan npm install atau npm run dev.
- Panduan lengkap tersedia di docs/PANDUAN_PENGGUNAAN.md.
