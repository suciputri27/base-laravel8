@extends('layouts.frontend')

@section('title', 'Struktur Organisasi - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Struktur Organisasi dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/struktur_organisasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Struktur Organisasi</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Struktur Organisasi</p>
    </div>
</div>
<section class="section">
    <div class="container">
        @forelse ($profil ?? [] as $item)
        <div class="struktur-frame mb-4">
            <img src="{{ asset('storage/' . $item->berkas) }}" alt="">
        </div>
        @empty
        <p class="text-secondary text-center">Alur Pengaduan belum tersedia.</p>
        @endforelse
    </div>
</section>
@endsection