@extends('layouts.frontend')

@section('title', 'Tentang Dinas - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Beranda dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/tentang.css') }}">
@endpush

@section('content')
<div class="page-hero">
    <div class="page-hero-content">
        <h1>Tentang Dinas</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Tentang Dinas</p>
    </div>
</div>
<section class="section">
    <div class="container">

        <!-- <div class="tentang-hero text-center">
            <h1>Tentang Dinas</h1>
            <p class="mb-0 fs-5">Dinas Kependudukan dan Pencatatan Sipil Kabupaten Agam</p>
        </div> -->

        <div class="row g-4 mb-4">
            <div class="col-12">
                <p class="fs-6 text-secondary">
                    {{ optional($profil)->tentang ?? 'Tentang Belum Tersedia' }}
                </p>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card tentang-info-card p-3">
                    <div class="card-body text-center">
                        <i class="ti ti-map-pin mb-3 d-block"></i>
                        <h5 class="card-title">Alamat Kantor</h5>
                        <p class="card-text text-secondary">
                            Padang Baru, Lubuk Basung, Kabupaten Agam, Sumatera Barat, 26452
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card tentang-info-card p-3">
                    <div class="card-body text-center">
                        <i class="ti ti-clock mb-3 d-block"></i>
                        <h5 class="card-title">Jam Layanan</h5>
                        <p class="card-text text-secondary">
                            Senin - Kamis : 08.00 - 16.00 WIB
                            <br>
                            Jum'at : 08.00 - 16.30 WIB
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card tentang-info-card p-3">
                    <div class="card-body text-center">
                        <i class="ti ti-phone mb-3 d-block"></i>
                        <h5 class="card-title">Kontak</h5>
                        <p class="card-text text-secondary">
                            {{ optional($profil)->no_telepon ?? '-' }}<br>
                            {{ optional($profil)->email ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection