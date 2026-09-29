@extends('layouts.frontend')

@section('title', 'Motto - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Motto dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/motto.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Motto</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Motto</p>
    </div>
</div>
<section class="section">
    <div class="container">
        <div class="motto-wrapper">
            <h1 class="mb-4">Motto Pelayanan</h1>
            <div class="motto-text">
                {{ optional($profil)->motto ?? 'Motto Belum Tersedia' }}
            </div>
            <p class="motto-sub">
                Komitmen Disdukcapil Kabupaten Agam dalam memberikan pelayanan administrasi
                kependudukan yang cepat, mudah, dan tanpa pungutan biaya.
            </p>
        </div>
    </div>
</section>
@endsection