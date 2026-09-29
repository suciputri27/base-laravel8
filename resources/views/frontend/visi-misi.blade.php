@extends('layouts.frontend')

@section('title', 'Visi Misi - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Visi Misi dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/visi_misi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Visi Misi Kabupaten Agam</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Visi Misi</p>
    </div>
</div>
<section class="section">
    <div class="container">

        <div class="visi-box text-center">
            <h2>Visi Kabupaten Agam</h2>
            <p class="fs-5 mb-0 fst-italic">
                {{ optional($profil)->visi ?? 'Visi Belum Tersedia' }}
            </p>
        </div>

        <h2 class="mb-3">Misi Kabupaten Agam</h2>
        <div class="misi-list">
            <div class="misi-item">
                <div>{{ optional($profil)->misi ?? 'Misi Belum Tersedia' }}</div>
            </div>
        </div>

    </div>
</section>
@endsection