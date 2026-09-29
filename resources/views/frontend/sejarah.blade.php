@extends('layouts.frontend')

@section('title', 'Sejarah - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Sejarah dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/sejarah.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Sejarah</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Sejarah</p>
    </div>
</div>
<section class="section">
    <div class="container">
        <p class="text-secondary mb-4">
            {{ optional($profil)->sejarah ?? 'Sejarah Belum Tersedia' }}
        </p>
    </div>
</section>
@endsection