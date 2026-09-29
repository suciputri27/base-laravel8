@extends('layouts.frontend')

@section('title', 'SOP - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman SOP dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/sop.css') }}">
<link rel="stylesheet" href="{{ asset('css/frontend/publikasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>SOP</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / SOP</p>
    </div>
</div>
<section class="section">
    <div class="container">
        <p class="text-secondary mb-4">
            Unduh dokumen SOP untuk setiap jenis layanan administrasi kependudukan dan pencatatan
            sipil di bawah ini.
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
    window.publikasiDataUrl = "{{ url('profil/sop_data') }}";
</script>
<script src="{{ asset('js/frontend/publikasi.js') }}"></script>
@endpush