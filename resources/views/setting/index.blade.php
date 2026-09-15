@extends('layouts.app')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')

@section('content')
    <form id="settingForm" action="{{ route('setting.update') }}" method="POST" novalidate>
        @csrf
        @method('PUT')

        <div class="bento-grid">
            <div class="bento-card span-12">
                <div class="settings-hero">
                    <div class="settings-hero-icon">
                        <i class="fas fa-cog"></i>
                    </div>
                    <div>
                        <h3>Pengaturan Website</h3>
                        <p>Kelola identitas, kontak, dan media sosial website Anda dalam satu halaman.</p>
                    </div>
                    <div class="ms-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>

            <div class="bento-card span-7">
                <div class="settings-section-title">
                    <i class="fas fa-globe"></i>
                    <h4>Informasi Umum</h4>
                </div>

                <div class="form-group">
                    <label class="form-label" for="nama_website">Nama Website</label>
                    <div class="input-icon">
                        <i class="fas fa-globe"></i>
                        <input type="text" name="nama_website" id="nama_website" class="form-control" value="{{ $setting->nama_website ?? '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tentang">Tentang Disdukcapil</label>
                    <textarea name="tentang" id="tentang" class="form-control" rows="4">{{ $setting->tentang ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tentang">Visi</label>
                    <textarea name="visi" id="visi" class="form-control" rows="4">{{ $setting->tentang ?? '' }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="tentang">Misi</label>
                    <textarea name="misi" id="misi" class="form-control" rows="6">{{ $setting->tentang ?? '' }}</textarea>
                </div>
            </div>

            <div class="bento-card span-5">
                <div class="settings-section-title">
                    <i class="fas fa-location-dot"></i>
                    <h4>Kontak &amp; Lokasi</h4>
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <div class="input-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" class="form-control" value="{{ $setting->email ?? '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="alamat">Alamat</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3">{{ $setting->alamat ?? '' }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="form-label" for="no_telepon">No. Telepon</label>
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                                <input type="tel" name="no_telepon" id="no_telepon" class="form-control" placeholder="(0752) 76501" value="{{ $setting->no_telepon ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="form-label" for="no_whatsapp">No. WhatsApp</label>
                            <div class="input-icon">
                                <i class="fab fa-whatsapp"></i>
                                <input type="tel" name="no_whatsapp" id="no_whatsapp" class="form-control" placeholder="+62 812-3456-7890" value="{{ $setting->no_whatsapp ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bento-card span-12">
                <div class="settings-section-title">
                    <i class="fas fa-share-nodes"></i>
                    <h4>Media Sosial</h4>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="form-label" for="twitter">Twitter</label>
                            <div class="input-icon">
                                <i class="fab fa-twitter"></i>
                                <input type="url" name="twitter" id="twitter" class="form-control" placeholder="https://twitter.com/username" value="{{ $setting->twitter ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="form-label" for="facebook">Facebook</label>
                            <div class="input-icon">
                                <i class="fab fa-facebook-f"></i>
                                <input type="url" name="facebook" id="facebook" class="form-control" placeholder="https://facebook.com/username" value="{{ $setting->facebook ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="form-label" for="youtube">YouTube</label>
                            <div class="input-icon">
                                <i class="fab fa-youtube"></i>
                                <input type="url" name="youtube" id="youtube" class="form-control" placeholder="https://youtube.com/@channel" value="{{ $setting->youtube ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label class="form-label" for="tiktok">TikTok</label>
                            <div class="input-icon">
                                <i class="fab fa-tiktok"></i>
                                <input type="url" name="tiktok" id="tiktok" class="form-control" placeholder="https://tiktok.com/@username" value="{{ $setting->tiktok ?? '' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        function formatMobilePhone(value) {
            var digits = String(value || '').replace(/\D/g, '');

            if (!digits) {
                return '';
            }

            if (digits.charAt(0) === '0') {
                digits = '62' + digits.slice(1);
            }

            if (digits.slice(0, 2) !== '62') {
                digits = '62' + digits;
            }

            digits = digits.slice(0, 14);

            var local = digits.slice(2);
            var formatted = '+62';

            if (local.length > 0) {
                formatted += ' ' + local.slice(0, 3);
            }
            if (local.length > 3) {
                formatted += '-' + local.slice(3, 7);
            }
            if (local.length > 7) {
                formatted += '-' + local.slice(7, 11);
            }

            return formatted;
        }

        function formatLandlinePhone(value) {
            var digits = String(value || '').replace(/\D/g, '');

            digits = digits.slice(0, 12);

            if (!digits) {
                return '';
            }

            var area = digits.slice(0, 4);
            var number = digits.slice(4);

            return number ? '(' + area + ') ' + number : '(' + area + ')';
        }

        (function () {
            var noTelepon = document.getElementById('no_telepon');
            var noWhatsapp = document.getElementById('no_whatsapp');

            if (noTelepon) {
                noTelepon.value = formatLandlinePhone(noTelepon.value);
                noTelepon.addEventListener('input', function () {
                    this.value = formatLandlinePhone(this.value);
                });
            }

            if (noWhatsapp) {
                noWhatsapp.value = formatMobilePhone(noWhatsapp.value);
                noWhatsapp.addEventListener('input', function () {
                    this.value = formatMobilePhone(this.value);
                });
            }
        })();

        document.getElementById('settingForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    App.alert('success', response.message);
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });
    </script>
@endpush
