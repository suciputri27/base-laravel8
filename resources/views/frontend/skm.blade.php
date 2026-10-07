{{--
    KONTRAK DATA:
    - Controller: App\Http\Controllers\Frontend\SkmController::index()
    - Variabel: $pertanyaans -> array hasil decode JSON dari API ajaxGetSurvei,
      tiap item: {id_survei, pertanyaan, pilihan: [{pilihan, pilihan_nilai, icon}]}
--}}
@extends('layouts.frontend')

@section('title', 'Survei Kepuasan Masyarakat - DISDUKCAPIL Kabupaten Agam')

@push('css')
<link rel="stylesheet" href="{{ asset('css/frontend/skm.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Survei Kepuasan Masyarakat</h1>
        <p>
            <a href="{{ url('beranda') }}" class="text-decoration-none color-primary">
                <i class="ti ti-home"></i> Beranda
            </a> / SKM
        </p>
    </div>
</div>

<section class="section">
    <div class="container">

        <div class="skm-intro">
            <h2><i class="ti ti-heart-handshake"></i> Pendapat Anda Sangat Berarti</h2>
            <p>
                Bantu kami meningkatkan kualitas pelayanan dengan mengisi survei singkat ini.
                Hanya butuh waktu kurang dari 2 menit.
            </p>
        </div>

        {{-- ====== FORM (disembunyikan setelah sukses submit) ====== --}}
        <div id="skm-form-wrapper" class="skm-form-card">

            <form id="form-skm">
                @csrf

                <div class="skm-section-label"><i class="ti ti-user"></i> Data Diri</div>

                <div class="row g-3 mb-3">
                    <input type="hidden" name="recaptcha_token" id="recaptcha_token">
                    <input type="hidden" name="kode_instansi" value="{{ $kode_instansi ?? '' }}">
                    <div class="col-md-6">
                        <label class="form-label">Nama</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Usia</label>
                        <input type="number" name="usia" class="form-control" min="1" max="120" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="" selected disabled>-- Pilih --</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pendidikan Terakhir</label>
                        <select name="pendidikan" class="form-select" required>
                            <option value="" selected disabled>-- Pilih --</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="D1">D1</option>
                            <option value="D3">D3</option>
                            <option value="D4">D4</option>
                            <option value="S1">S1</option>
                            <option value="S2">S2</option>
                            <option value="S3">S3</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Pekerjaan</label>
                        <select name="pekerjaan" class="form-select" required>
                            <option value="" selected disabled>-- Pilih --</option>
                            <option value="Masyarakat">Masyarakat</option>
                            <option value="Pelaku Usaha">Pelaku Usaha</option>
                            <option value="OPD">OPD</option>
                            <option value="PPK">PPK</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ruangan/Loket (opsional)</label>
                        <input type="text" name="ruangan" class="form-control">
                    </div>
                </div>

                <div class="skm-section-label"><i class="ti ti-clipboard-check"></i> Penilaian Pelayanan</div>

                @forelse ($pertanyaans ?? [] as $i => $p)
                <div class="skm-question" data-question-index="{{ $i }}">
                    <p class="q-text"><span class="q-number">{{ $i + 1 }}</span>{{ $p['pertanyaan'] }}</p>

                    <div class="skm-options">
                        @foreach ($p['pilihan'] as $j => $opt)
                        <input type="radio"
                            id="q{{ $i }}-opt{{ $j }}"
                            name="jawaban[{{ $i }}]"
                            value="{{ $opt['pilihan_nilai'] }}"
                            required>
                        <label for="q{{ $i }}-opt{{ $j }}">
                            <span class="opt-icon">{{ $opt['icon'] ?? '⭐' }}</span>
                            {{ $opt['pilihan'] }}
                        </label>
                        @endforeach
                    </div>
                </div>
                @empty
                <p class="text-secondary text-center">Pertanyaan survei belum tersedia. Coba muat ulang halaman.</p>
                @endforelse

                <button type="submit" class="skm-btn-submit" id="skm-submit-btn">
                    <i class="ti ti-send"></i> Kirim Survei
                </button>
            </form>
        </div>

        {{-- ====== TAMPILAN SUKSES (muncul setelah submit berhasil) ====== --}}
        <div id="skm-success" class="skm-form-card skm-success d-none">
            <i class="ti ti-circle-check"></i>
            <h3>Terima Kasih!</h3>
            <p class="text-secondary">Survei Anda berhasil dikirim dan sangat berarti bagi kami.</p>
            <a href="{{ url('skm/hasil') }}" class="btn btn-custom mt-2">
                <i class="ti ti-chart-bar"></i> Lihat Hasil Rekap SKM
            </a>
        </div>

    </div>
</section>

@endsection

@push('js')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key_skm') }}" async defer></script>
<script>
    const RECAPTCHA_SITE_KEY_SKM = "{{ config('services.recaptcha.site_key_skm') }}";
    document.addEventListener('DOMContentLoaded', function() {
        // Kasih feedback visual (border highlight) begitu pertanyaan dijawab
        document.querySelectorAll('.skm-question').forEach(function(q) {
            q.querySelectorAll('input[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    q.classList.add('answered');
                });
            });
        });

        const form = document.getElementById('form-skm');
        const submitBtn = document.getElementById('skm-submit-btn');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ti ti-loader-2"></i> Mengirim...';

            const formData = new FormData(form);


            grecaptcha.ready(function() {
                grecaptcha.execute(RECAPTCHA_SITE_KEY_SKM, {
                        action: 'ikm_submit'
                    })
                    .then(function(token) {
                        document.getElementById('recaptcha_token').value = token;
                        kirimForm();
                    })
                    .catch(function(err) {
                        console.error('Gagal generate token reCAPTCHA:', err);
                        showToast('error', 'Verifikasi keamanan gagal. Silakan coba lagi.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="ti ti-send"></i> Kirim Survei';
                    });
            });

            // ===== LANGKAH 2: baru submit form (setelah token didapat) =====
            function kirimForm() {
                submitBtn.innerHTML = '<i class="ti ti-loader-2"></i> Mengirim...';

                const formData = new FormData(form);

                fetch("{{ url('skm/submit') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        },
                        body: formData,
                    })
                    .then(function(res) {
                        return res.json().then(function(data) {
                            return {
                                status: res.status,
                                body: data
                            };
                        });
                    })
                    .then(function(result) {
                        if (result.body.success) {
                            showToast('success', result.body.message || 'Survei berhasil dikirim!');
                            document.getElementById('skm-form-wrapper').classList.add('d-none');
                            document.getElementById('skm-success').classList.remove('d-none');
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                        } else {
                            showToast('error', result.body.message || 'Terjadi kesalahan. Silakan coba lagi.');
                            submitBtn.disabled = false;
                            submitBtn.innerHTML = '<i class="ti ti-send"></i> Kirim Survei';
                        }
                    })
                    .catch(function(err) {
                        console.error('Gagal submit SKM:', err);
                        showToast('error', 'Terjadi kesalahan jaringan. Silakan coba lagi.');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="ti ti-send"></i> Kirim Survei';
                    });
            }
        });
    });
</script>
@endpush