@extends('layouts.frontend')

@section('title', 'Alur Pengaduan - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Alur Pengaduan dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/alur_pengaduan.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Alur Pengaduan</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Alur Pengaduan</p>
    </div>
</div>

<section class="section">
    <div class="container">
        @foreach ($profil ?? [] as $item)
        <div class="struktur-frame mb-4">
            <img src="{{ asset('storage/' . $item->berkas) }}" alt="">
        </div>
        @endforeach
    </div>
</section>
@endsection