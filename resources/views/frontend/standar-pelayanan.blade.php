@extends('layouts.frontend')

@section('title', 'Standar Pelayanan Publik - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Standar Pelayanan Publik dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/standar_pelayanan.css') }}">
<link rel="stylesheet" href="{{ asset('css/frontend/publikasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Standar Pelayanan Publik</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Standar Pelayanan Publik</p>
    </div>
</div>

<section class="section">
    <div class="container">
        <p class="text-secondary mb-4">
            Standar pelayanan disusun sesuai Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan
            Publik, mencakup dasar hukum, persyaratan, jangka waktu, dan biaya untuk setiap jenis
            layanan administrasi kependudukan.
        </p>
        <div class="publikasi-search">
            <i class="ti ti-search"></i>
            <input type="text" id="publikasi-search-input" class="form-control"
                placeholder="Cari judul atau deskripsi standar pelayanan...">
        </div>
        <div id="publikasi-container" class="row g-4">
            //
        </div>
        <div id="publikasi-pagination" class="publikasi-pagination"></div>
    </div>
</section>
@endsection

@push('js')
<script>
    window.publikasiDataUrl = "{{ url('profil/standar_pelayanan_data') }}";
</script>
<script src="{{ asset('js/frontend/publikasi.js') }}"></script>
@endpush