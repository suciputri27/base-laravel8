{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\JadwalController::index()
    - $jadwalRutin    -> Collection jenis=1, sudah diurutkan Senin-Minggu
    - $jadwalKeliling -> Collection jenis=2, diurutkan berdasarkan tanggal
--}}
@extends('layouts.frontend')

@section('title', 'Jadwal Layanan - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/jadwal.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Jadwal Layanan</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / Jadwal Layanan
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        {{-- Toggle Rutin / Keliling --}}
        <div class="jadwal-toggle">
            <button type="button" class="active" data-target="rutin">
                <i class="ti ti-calendar-time"></i> Jadwal Rutin
            </button>
            <button type="button" data-target="keliling">
                <i class="ti ti-car"></i> Jadwal Keliling
            </button>
        </div>

        {{-- ================= JADWAL RUTIN ================= --}}
        <div id="jadwal-rutin" class="jadwal-panel">
            <div class="text-center mb-4">
                <h2 class="section-title">Pelayanan Rutin Mingguan</h2>
                <p class="section-description">
                    Jadwal buka-tutup layanan di kantor Disdukcapil, berulang tiap minggu.
                </p>
            </div>

            <div class="rutin-grid">
                @forelse ($jadwalRutin ?? [] as $i => $jadwal)

                <div class="rutin-card day-{{ $i % 7 }}">

                    @if ($jadwal->is_today)
                    <span class="badge-hari-ini">Hari Ini</span>
                    @endif

                    <div class="hari-icon">
                        <i class="ti ti-calendar-event"></i>
                    </div>

                    <div class="hari-nama">
                        {{ $jadwal->day_label }}
                    </div>

                    <div class="jam-wrapper">
                        {{ \Illuminate\Support\Str::limit($jadwal->open, 5, '') }}
                        &ndash;
                        {{ \Illuminate\Support\Str::limit($jadwal->close, 5, '') }}
                        <span class="jam-label">WIB</span>
                    </div>

                </div>
                @empty
                <p class="text-secondary text-center">
                    Jadwal rutin belum tersedia.
                </p>

                @endforelse
            </div>
        </div>

        {{-- ================= JADWAL KELILING ================= --}}
        <div id="jadwal-keliling" class="jadwal-panel d-none">
            <div class="text-center mb-4">
                <h2 class="section-title">Pelayanan Keliling</h2>
                <p class="section-description">
                    Jadwal layanan jemput bola yang datang langsung ke nagari/kecamatan Anda.
                </p>
            </div>

            <div class="keliling-timeline" id="keliling-timeline">

                @forelse ($jadwalKeliling ?? [] as $jadwal)

                <div class="keliling-item">

                    <div class="keliling-date-box">
                        <div class="tanggal-angka">
                            {{ $jadwal->tanggal_angka }}
                        </div>

                        <div class="tanggal-bulan">
                            {{ $jadwal->tanggal_bulan }}
                        </div>

                        <div class="tanggal-hari">
                            {{ $jadwal->day_label }}
                        </div>
                    </div>

                    <div class="keliling-info">

                        <div class="tempat-nama">
                            <i class="ti ti-map-pin"></i>
                            {{ $jadwal->tempat }}
                        </div>

                        <div class="jam-info">
                            <i class="ti ti-clock"></i>
                            {{ \Illuminate\Support\Str::limit($jadwal->open, 5, '') }}
                            &ndash;
                            {{ \Illuminate\Support\Str::limit($jadwal->close, 5, '') }}
                            WIB
                        </div>

                    </div>

                    @if ($jadwal->status === 'lewat')
                    <span class="badge-lewat">
                        {{ $jadwal->status_label }}
                    </span>

                    @elseif ($jadwal->status === 'hari_ini')

                    <span class="badge-sekarang">
                        {{ $jadwal->status_label }}
                    </span>

                    @elseif ($jadwal->status === 'segera')

                    <span class="badge-segera">
                        {{ $jadwal->status_label }}
                    </span>

                    @endif

                </div>
                @empty
                <p class="text-secondary text-center">Jadwal keliling belum tersedia.</p>
                @endforelse
            </div>

            @if ($jadwalKeliling && $jadwalKeliling->hasMorePages())
            <div class="text-center mt-3">
                <button
                    type="button"
                    id="btn-muat-keliling"
                    class="btn btn-custom"
                    data-next-page="2">
                    <i class="ti ti-chevron-down"></i>
                    Muat Lebih Banyak
                </button>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.jadwal-toggle button');
        const panels = {
            rutin: document.getElementById('jadwal-rutin'),
            keliling: document.getElementById('jadwal-keliling'),
        };

        buttons.forEach(function(btn) {
            btn.addEventListener('click', function() {
                buttons.forEach(function(b) {
                    b.classList.remove('active');
                });
                btn.classList.add('active');

                const target = btn.getAttribute('data-target');
                Object.keys(panels).forEach(function(key) {
                    panels[key].classList.toggle('d-none', key !== target);
                });
            });
        });

        // ===== Muat Lebih Banyak: Jadwal Keliling =====
        const btnMuat = document.getElementById('btn-muat-keliling');
        if (btnMuat) {
            const bulanIndo = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            function escapeHtml(str) {
                const div = document.createElement('div');
                div.textContent = str ?? '';
                return div.innerHTML;
            }

            function formatJam(jam) {
                return (jam ?? '').toString().substring(0, 5);
            }

            function buildBadge(tanggalStr) {
                const tanggal = new Date(tanggalStr);
                const now = new Date();
                tanggal.setHours(0, 0, 0, 0);
                now.setHours(0, 0, 0, 0);

                const diffDays = Math.round((tanggal - now) / (1000 * 60 * 60 * 24));

                if (diffDays < 0) return '<span class="badge-lewat">Sudah Lewat</span>';
                if (diffDays === 0) return '<span class="badge-sekarang">Hari Ini</span>';
                if (diffDays <= 7) return '<span class="badge-segera">Segera</span>';
                return '';
            }

            function renderItem(item) {
                const tanggal = new Date(item.tanggal);
                const angka = String(tanggal.getDate()).padStart(2, '0');
                const bulan = bulanIndo[tanggal.getMonth()];

                return (
                    '<div class="keliling-item">' +
                    '<div class="keliling-date-box">' +
                    '<div class="tanggal-angka">' + angka + '</div>' +
                    '<div class="tanggal-bulan">' + bulan + '</div>' +
                    '</div>' +
                    '<div class="keliling-info">' +
                    '<div class="tempat-nama"><i class="ti ti-map-pin"></i> ' + escapeHtml(item.tempat) + '</div>' +
                    '<div class="jam-info"><i class="ti ti-clock"></i> ' +
                    formatJam(item.open) + ' &ndash; ' + formatJam(item.close) + ' WIB' +
                    '</div>' +
                    '</div>' +
                    buildBadge(item.tanggal) +
                    '</div>'
                );
            }

            btnMuat.addEventListener('click', function() {
                const page = parseInt(btnMuat.getAttribute('data-next-page'), 10);
                const timeline = document.getElementById('keliling-timeline');

                btnMuat.disabled = true;
                btnMuat.innerHTML = '<i class="ti ti-loader-2"></i> Memuat...';

                fetch("{{ url('jadwal/keliling') }}?page=" + page)
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(res) {
                        if (res.success && res.data.length > 0) {
                            timeline.insertAdjacentHTML('beforeend', res.data.map(renderItem).join(''));
                        }

                        if (res.has_more) {
                            btnMuat.setAttribute('data-next-page', page + 1);
                            btnMuat.disabled = false;
                            btnMuat.innerHTML = '<i class="ti ti-chevron-down"></i> Muat Lebih Banyak';
                        } else {
                            btnMuat.remove(); // nggak ada lagi data, sembunyikan tombol
                        }
                    })
                    .catch(function(err) {
                        console.error('Gagal memuat jadwal keliling:', err);
                        btnMuat.disabled = false;
                        btnMuat.innerHTML = '<i class="ti ti-chevron-down"></i> Muat Lebih Banyak';
                    });
            });
        }
    });
</script>
@endpush