@extends('layouts.frontend')

@section('title', 'Survey Kepuasan Masyarakat - DISDUKCAPIL Kabupaten Agam')

@push('css')
{{-- CSS khusus halaman ini, hanya termuat saat halaman SKM dibuka --}}
<link rel="stylesheet" href="{{ asset('css/frontend/skm.css') }}">
@endpush

@section('content')

<div class="page-hero">
    <div class="page-hero-content">
        <h1>Survey Kepuasan Masyarakat (SKM)</h1>
        <p><a href="index.php" class="text-decoration-none color-primary"><i class="ti ti-home"></i> Beranda</a> / Profil / Survey Kepuasan Masyarakat (SKM)</p>
    </div>
</div>
<section class="section">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <!-- Contoh ringkasan skor SKM periode berjalan. Ganti angka dengan hasil rekap asli. -->
                <div class="skm-summary-card">
                    <div>
                        <div class="skm-score-label">NILAI SKM TRIWULAN INI</div>
                        <div class="skm-score">88,4 <small style="font-size:1rem;">/ 100</small></div>
                    </div>
                    <div class="text-end">
                        <div class="skm-score-label">KATEGORI</div>
                        <div class="fs-5 fw-bold">Sangat Baik (A)</div>
                    </div>
                </div>

                <div id="skm-alert" class="alert d-none"></div>

                <!--
                    CATATAN: masih HTML statis, belum ada backend.
                    Ganti action="#" dengan endpoint backend kamu, contoh:
                    action="proses-skm.php" method="post"
                -->
                <form id="form-skm" action="#" method="post" novalidate>

                    <div class="mb-3">
                        <label class="form-label">Nama (opsional)</label>
                        <input type="text" name="nama" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Jenis Layanan yang Diterima</label>
                        <select name="jenis_layanan" class="form-select" required>
                            <option value="" selected disabled>-- Pilih jenis layanan --</option>
                            <option value="ktp">KTP-el</option>
                            <option value="kk">Kartu Keluarga</option>
                            <option value="akta_lahir">Akta Kelahiran</option>
                            <option value="akta_mati">Akta Kematian</option>
                            <option value="pindah">Pindah Datang</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <hr class="my-4">

                    <div class="skm-unsur-card">
                        <div class="unsur-title">1. Persyaratan pelayanan</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u1" id="u1-1" value="1" required><label for="u1-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u1" id="u1-2" value="2"><label for="u1-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u1" id="u1-3" value="3"><label for="u1-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u1" id="u1-4" value="4"><label for="u1-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">2. Sistem, mekanisme, dan prosedur pelayanan</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u2" id="u2-1" value="1" required><label for="u2-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u2" id="u2-2" value="2"><label for="u2-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u2" id="u2-3" value="3"><label for="u2-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u2" id="u2-4" value="4"><label for="u2-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">3. Waktu penyelesaian pelayanan</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u3" id="u3-1" value="1" required><label for="u3-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u3" id="u3-2" value="2"><label for="u3-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u3" id="u3-3" value="3"><label for="u3-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u3" id="u3-4" value="4"><label for="u3-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">4. Biaya/tarif pelayanan</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u4" id="u4-1" value="1" required><label for="u4-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u4" id="u4-2" value="2"><label for="u4-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u4" id="u4-3" value="3"><label for="u4-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u4" id="u4-4" value="4"><label for="u4-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">5. Produk spesifikasi jenis pelayanan</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u5" id="u5-1" value="1" required><label for="u5-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u5" id="u5-2" value="2"><label for="u5-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u5" id="u5-3" value="3"><label for="u5-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u5" id="u5-4" value="4"><label for="u5-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">6. Kompetensi pelaksana</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u6" id="u6-1" value="1" required><label for="u6-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u6" id="u6-2" value="2"><label for="u6-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u6" id="u6-3" value="3"><label for="u6-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u6" id="u6-4" value="4"><label for="u6-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">7. Perilaku pelaksana</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u7" id="u7-1" value="1" required><label for="u7-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u7" id="u7-2" value="2"><label for="u7-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u7" id="u7-3" value="3"><label for="u7-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u7" id="u7-4" value="4"><label for="u7-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">8. Penanganan pengaduan, saran, dan masukan</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u8" id="u8-1" value="1" required><label for="u8-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u8" id="u8-2" value="2"><label for="u8-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u8" id="u8-3" value="3"><label for="u8-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u8" id="u8-4" value="4"><label for="u8-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="skm-unsur-card">
                        <div class="unsur-title">9. Kualitas sarana dan prasarana</div>
                        <div class="skm-rating-group">
                            <div class="skm-rating-option"><input type="radio" name="u9" id="u9-1" value="1" required><label for="u9-1">Tidak Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u9" id="u9-2" value="2"><label for="u9-2">Kurang Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u9" id="u9-3" value="3"><label for="u9-3">Baik</label></div>
                            <div class="skm-rating-option"><input type="radio" name="u9" id="u9-4" value="4"><label for="u9-4">Sangat Baik</label></div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Saran/Masukan (opsional)</label>
                        <textarea name="saran" class="form-control" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-custom w-100">
                        <i class="ti ti-send"></i> Kirim Survei
                    </button>

                </form>

            </div>
        </div>
    </div>
</section>
<script src="js/include.js"></script>

<script>
    document.getElementById('form-skm').addEventListener('submit', function(e) {
        e.preventDefault();
        var form = e.target;
        var alertBox = document.getElementById('skm-alert');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // TODO: kirim ke backend, contoh dengan fetch:
        // const formData = new FormData(form);
        // fetch('proses-skm.php', { method: 'POST', body: formData });

        alertBox.className = 'alert alert-success';
        alertBox.innerHTML = '<i class="ti ti-circle-check"></i> Terima kasih, survei Anda berhasil dikirim (simulasi). Hubungkan form ini ke backend untuk menyimpan data dan menghitung skor SKM sesungguhnya.';
        alertBox.classList.remove('d-none');
        form.reset();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>
@endsection