{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\BeritaController::index()
    - Variabel: $beritas (Collection semua berita, eager load 'kategori')
--}}
@extends('layouts.frontend')

@section('title', 'Informasi - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/informasi.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Informasi</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / Informasi
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <div class="row g-4">
            @forelse ($posts ?? [] as $item)
            <div class="col-sm-6 col-lg-4">
                <a href="{{ url('informasi', $item->slug) }}" class="text-decoration-none">
                    <div class="card berita-card"> 
                    @php $foto = $item->berkas->first(); @endphp
                    @if ($foto)
                    <img src="{{ storage_url($foto->berkas) }}" alt="{{ $item->title }}" class="berita-detail-img">
                    @endif
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="kategori-badge">{{ $item->kategori->name ?? 'Umum' }}</div>
                                <div class="tanggal">
                                    <i class="ti ti-calendar me-1"></i>
                                    {{ optional($item->created_at)->translatedFormat('d F Y') }}
                                </div>
                            </div>
                            <h3 class="berita-title">{{ $item->title }}</h3>
                        </div>
                    </div>
                </a>
            </div>
            @empty
            <div class="col-12">
                <p class="text-secondary text-center">Belum ada informasi.</p>
            </div>
            @endforelse
        </div>

    </div>
</section>

@endsection