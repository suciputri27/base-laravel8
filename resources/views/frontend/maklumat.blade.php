@extends('layouts.frontend')

@section('title', 'Maklumat Pelayanan - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Maklumat Pelayanan dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/maklumat.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Maklumat Pelayanan</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Maklumat Pelayanan</p>
    </div>
</div>
<section class="section">
    <div class="container py-4">
        <div class="maklumat-box mx-auto" style="max-width: 700px;">
            <i class="ti ti-shield-check"></i>
            <p class="mb-0">
                {{ optional($profil)->deskripsi ?? 'Maklumat Belum Tersedia' }}
            </p>
            <div class="maklumat-sign">
                Kepala Dinas Kependudukan dan Pencatatan Sipil<br>
                Kabupaten Agam
            </div>
        </div>
    </div>
</section>
@endsection