{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\F_PersyaratanController::index()
    - Variabel yang dikirim: $pelayanans
      -> Collection dari model F_Pelayanan (tabel: pelayanan), sudah eager
         load relasi persyaratans() (tabel: persyaratan, lewat pivot
         detail_persyaratan).
    - Tiap item pelayanan punya: nama_pelayanan, deskripsi, dan
      persyaratans (collection dari model F_Persyaratan, tiap item punya
      nama_persyaratan).
--}}
@extends('layouts.frontend')

@section('title', 'Persyaratan - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman Persyaratan dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/persyaratan.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Persyaratan</h1>
        <p>
            <a href="{{ route('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / Pelayanan / Persyaratan
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <!-- <h1 class="mb-4">Persyaratan Layanan</h1> -->
        <p class="text-secondary mb-4">
            Berikut persyaratan dokumen untuk masing-masing jenis layanan administrasi kependudukan
            dan pencatatan sipil. Klik jenis layanan untuk melihat detail persyaratannya.
        </p>

        <div class="accordion persyaratan-accordion" id="persyaratanAccordion">
            @forelse ($pelayanans ?? [] as $i => $pelayanan)
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button {{ $i === 0 ? '' : 'collapsed' }}" type="button"
                        data-bs-toggle="collapse" data-bs-target="#psy{{ $pelayanan->id }}">
                        {{ $loop->iteration }}. {{ $pelayanan->nama_pelayanan }}
                    </button>
                </h2>
                <div id="psy{{ $pelayanan->id }}"
                    class="accordion-collapse collapse {{ $i === 0 ? 'show' : '' }}"
                    data-bs-parent="#persyaratanAccordion">
                    <div class="accordion-body">
                        @if ($pelayanan->deskripsi)
                        <p class="text-secondary small mb-3">{{ $pelayanan->deskripsi }}</p>
                        <hr>
                        @endif

                        @if ($pelayanan->persyaratans->isNotEmpty())
                        <ul class="list-group">
                            @foreach ($pelayanan->persyaratans as $item)
                            <li class="list-group-item justify-content-between">
                                <div>
                                    <i class="ti ti-circle-check"></i>
                                    <span>{{ $item->nama_persyaratan }}</span>
                                </div>
                                @if ($item->cekdokumen == 1)
                                <a href="{{ asset('storage/' . $item->pivot->berkas) }}" class="btn btn-custom mt-auto" download><i class="ti ti-download me-1"></i> Unduh Format </a>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-secondary small mb-0">Belum ada persyaratan untuk layanan ini.</p>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-secondary">Belum ada data pelayanan/persyaratan.</p>
            @endforelse
        </div>

    </div>
</section>

@endsection