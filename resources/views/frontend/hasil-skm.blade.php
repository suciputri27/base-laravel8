{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\SkmController::hasil()
    - $grafik       -> {unsur: [...9 teks pertanyaan], nilai_unsur: [...9 angka 0-100], hasil: angka}
    - $detailUnsur  -> hasil ajaxCountDetailUnsur (struktur exact belum 100% dipastikan,
                        cek dd($detailUnsur) kalau tampilannya aneh, sesuaikan loop-nya)
    - $surveyor     -> {jenis_kelamin:[...], pendidikan:[...], pekerjaan:[...], jumlah_responden: int}
    - $dataIkm      -> {nilai_responden:[...], nilai_unsur:[...], nilai_tertimbang:[...]}
--}}
@extends('layouts.frontend')

@section('title', 'Hasil SKM - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/hasil-skm.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Hasil Survei Kepuasan Masyarakat</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> /
            <a href="{{ url('skm') }}" class="text-decoration-none color-primary">SKM</a> /
            Hasil
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="cta-survey-banner mb-5">
            <div class="cta-icon">
                <i class="ti ti-message-star"></i>
            </div>
            <h4>Belum Mengisi Survei?</h4>
            <p>Penilaian Anda membantu kami terus meningkatkan kualitas pelayanan.</p>
            <a href="{{ route('skm') }}" class="btn-cta-survey">
                <i class="ti ti-pencil"></i> Isi Survei Sekarang
            </a>
        </div>

        @php
        $skor = $grafik['hasil'] ?? 0;

        // Kategori sesuai konversi IKM (Permenpan RB No. 14/2017)
        if ($skor >= 88.31) {
        $kategori = 'A - Sangat Baik';
        } elseif ($skor >= 76.61) {
        $kategori = 'B - Baik';
        } elseif ($skor >= 65.00) {
        $kategori = 'C - Kurang Baik';
        } else {
        $kategori = 'D - Tidak Baik';
        }
        @endphp

        <div class="hasil-score-card">
            <div class="score-label">NILAI INDEKS KEPUASAN MASYARAKAT</div>
            <div class="score-number">{{ number_format($skor, 2) }}</div>
            <div class="score-kategori">{{ $kategori }}</div>
        </div>

        <div class="row g-4">

            {{-- ========== GRAFIK NILAI PER UNSUR ========== --}}
            <div class="col-lg-6">

                <div class="hasil-panel">
                    <h5><i class="ti ti-chart-bar"></i> Nilai per Unsur Pelayanan</h5>

                    @php
                    // Helper kecil: tentukan kategori (a/b/c/d) + label dari nilai 0-100.
                    // Skala sama persis kayak kategori skor utama (Permenpan RB 14/2017).
                    $kategoriUnsur = function (float $nilai): array {
                    if ($nilai >= 88.31) return ['kategori-a', 'Sangat Baik'];
                    if ($nilai >= 76.61) return ['kategori-b', 'Baik'];
                    if ($nilai >= 65.00) return ['kategori-c', 'Kurang Baik'];
                    return ['kategori-d', 'Tidak Baik'];
                    };

                    $listNilai = $grafik['nilai_unsur'] ?? [];
                    $jumlahBaik = collect($listNilai)->filter(fn ($n) => $n >= 76.61)->count();
                    $jumlahKurang = collect($listNilai)->filter(fn ($n) => $n < 65.00)->count();
                        @endphp

                        {{-- Ringkasan cepat --}}
                        @if (count($listNilai) > 0)
                        <div class="unsur-summary">
                            <div class="summary-chip chip-baik">
                                <span class="chip-angka">{{ $jumlahBaik }}</span>
                                dari {{ count($listNilai) }} unsur Baik/Sangat Baik
                            </div>
                            @if ($jumlahKurang > 0)
                            <div class="summary-chip chip-kurang">
                                <span class="chip-angka">{{ $jumlahKurang }}</span>
                                unsur perlu perbaikan
                            </div>
                            @endif
                        </div>
                        @endif

                        @forelse ($grafik['unsur'] ?? [] as $i => $namaUnsur)
                        @php
                        $nilai = (float) ($grafik['nilai_unsur'][$i] ?? 0);
                        [$kelasKategori, $labelKategori] = $kategoriUnsur($nilai);
                        @endphp
                        <div class="unsur-bar-item">
                            <div class="unsur-label">
                                <span class="unsur-teks">{{ $i + 1 }}. {{ $namaUnsur }}</span>
                                <span class="unsur-nilai-wrap">
                                    <span class="unsur-angka {{ $kelasKategori }}">{{ number_format($nilai, 0) }}%</span><br>
                                    <span class="badge-kategori-mini {{ $kelasKategori }}">{{ $labelKategori }}</span>
                                </span>
                            </div>
                            <div class="unsur-track">
                                <div class="unsur-fill {{ $kelasKategori }}" style="width: {{ $nilai }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-secondary">Data grafik belum tersedia.</p>
                        @endforelse
                </div>
            </div>

            {{-- ========== DEMOGRAFI RESPONDEN ========== --}}
            <div class="col-lg-6">
                <div class="hasil-panel">
                    <h5><i class="ti ti-users"></i> Demografi Responden</h5>

                    <div class="hasil-responden-count">
                        <div class="angka">{{ $surveyor['jumlah_responden'] ?? 0 }}</div>
                        <div class="label">Total Responden</div>
                    </div>

                    <p class="text-secondary small fw-bold mb-2">Jenis Kelamin</p>
                    @forelse ($surveyor['jenis_kelamin'] ?? [] as $jk)
                    <div class="demografi-bar-item">
                        <div class="demo-label">
                            <span>{{ $jk['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            <span>{{ number_format($jk['persentase'], 1) }}%</span>
                        </div>
                        <div class="demo-track">
                            <div class="demo-fill" style="width: {{ $jk['persentase'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-secondary small">Belum ada data.</p>
                    @endforelse

                    <p class="text-secondary small fw-bold mb-2 mt-3">Pendidikan</p>
                    @forelse ($surveyor['pendidikan'] ?? [] as $p)
                    <div class="demografi-bar-item">
                        <div class="demo-label">
                            <span>{{ $p['pendidikan'] }}</span>
                            <span>{{ number_format($p['persentase'], 1) }}%</span>
                        </div>
                        <div class="demo-track">
                            <div class="demo-fill" style="width: {{ $p['persentase'] }}%"></div>
                        </div>
                    </div>
                    @empty
                    <p class="text-secondary small">Belum ada data.</p>
                    @endforelse

                    <p class="text-secondary small fw-bold mb-2 mt-3">Pekerjaan</p>
                    @forelse ($surveyor['pekerjaan'] ?? [] as $pk)
                    @if ($pk['pekerjaan'])
                    <div class="demografi-bar-item">
                        <div class="demo-label">
                            <span>{{ $pk['pekerjaan'] }}</span>
                            <span>{{ number_format($pk['persentase'], 1) }}%</span>
                        </div>
                        <div class="demo-track">
                            <div class="demo-fill" style="width: {{ $pk['persentase'] }}%"></div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <p class="text-secondary small">Belum ada data.</p>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- ========== DETAIL SEBARAN NILAI PER UNSUR ========== --}}
        <div class="hasil-panel mt-2">
            <h5><i class="ti ti-list-details"></i> Detail Sebaran Nilai per Unsur</h5>
            <p class="text-secondary small">
                Jumlah responden yang memberi nilai 1 (Tidak Baik) sampai 4 (Sangat Baik) untuk tiap unsur.
            </p>

            {{--
                    CATATAN: struktur persis $detailUnsur dari ajaxCountDetailUnsur
                    belum 100% dipastikan. Kalau tampilan di bawah ini kosong/aneh,
                    uncomment baris @dd di bawah buat lihat struktur asli datanya,
                    lalu sesuaikan loop-nya.
                --}}
            {{-- @dd($detailUnsur) --}}

            <div class="table-responsive">
                <table class="table table-sm table-borderless align-middle">
                    <thead>
                        <tr class="text-secondary small">
                            <th>Unsur</th>
                            <th class="text-center">Nilai 1</th>
                            <th class="text-center">Nilai 2</th>
                            <th class="text-center">Nilai 3</th>
                            <th class="text-center">Nilai 4</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detailUnsur ?? [] as $row)
                        <tr>
                            <td class="small">{{ \Illuminate\Support\Str::limit($row[0] ?? '-', 50) }}</td>
                            <td class="text-center">{{ $row[1]['1'] ?? $row[1][1] ?? 0 }}</td>
                            <td class="text-center">{{ $row[1]['2'] ?? $row[1][2] ?? 0 }}</td>
                            <td class="text-center">{{ $row[1]['3'] ?? $row[1][3] ?? 0 }}</td>
                            <td class="text-center">{{ $row[1]['4'] ?? $row[1][4] ?? 0 }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary">Data belum tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</section>

@endsection