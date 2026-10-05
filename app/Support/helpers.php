<?php

use App\Helpers\IdEncryptionHelper;
use App\Helpers\IndonesianDateHelper;

if (! function_exists('id_encode')) {
    function id_encode(int $id): string
    {
        return IdEncryptionHelper::encode($id);
    }
}

if (! function_exists('id_decode')) {
    function id_decode(string $hash): ?int
    {
        return IdEncryptionHelper::decode($hash);
    }
}

if (! function_exists('indo_date')) {
    function indo_date($value): string
    {
        return IndonesianDateHelper::date($value);
    }
}

if (! function_exists('indo_datetime')) {
    function indo_datetime($value): string
    {
        return IndonesianDateHelper::dateTime($value);
    }
}

if (! function_exists('menu_url')) {
    function menu_url($routeOrUrl): string
    {
        if (! $routeOrUrl) {
            return '#';
        }

        if (filter_var($routeOrUrl, FILTER_VALIDATE_URL)) {
            return $routeOrUrl;
        }

        try {
            return route($routeOrUrl);
        } catch (\Throwable $e) {
            return '#';
        }
    }
}

if (! function_exists('storage_url')) {
    function storage_url(string $path): string
    {
        return '/storage/' . ltrim($path, '/');
    }
}

if (! function_exists('menu_is_active')) {
    function menu_is_active($routeOrUrl): bool
    {
        if (! $routeOrUrl) {
            return false;
        }

        if (request()->routeIs($routeOrUrl)) {
            return true;
        }

        if (substr($routeOrUrl, -6) === '.index') {
            $prefix = explode('.', $routeOrUrl)[0];

            return request()->routeIs($prefix . '.*');
        }

        return false;
    }
}

if (! function_exists('nama_hari')) {
    function nama_hari($angka): string
    {
        $hari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu',
        ];

        return $hari[(int) $angka] ?? 'Tidak diketahui';
    }
}
