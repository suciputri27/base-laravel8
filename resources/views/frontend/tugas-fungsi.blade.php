@extends('layouts.frontend')

@section('title', 'Tugas dan Fungsi - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Tugas dan Fungsi dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/tugas-fungsi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Tugas dan Fungsi</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Tugas dan Fungsi</p>
    </div>
</div>
<section class="section">
    <div class="container">
        <div class="tf-legal-note mb-4">
            Tugas dan fungsi ini disusun berdasarkan Peraturan Daerah/Peraturan Bupati Kabupaten Agam
            tentang Organisasi dan Tata Kerja Dinas Kependudukan dan Pencatatan Sipil. Sesuaikan dengan
            peraturan terbaru yang berlaku.
        </div>
        <hr>
        <p class="text-secondary mb-4">
            {{ optional($profil)->tupoksi ?? 'Tupoksi Belum Tersedia' }}
        </p>
    </div>
</section>
@endsection