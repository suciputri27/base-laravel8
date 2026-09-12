
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($post) ? 'Edit Profile' : 'Tambah Profile' }}</h5>
    </div>
    <div class="card-body">
        <form id="postForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($formMethod === 'PUT')
                <input type="hidden" name="_method" value="PUT">
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Nama Website</label>
                        <input type="text" name="nama_website" id="nama_website" class="form-control" value="{{ isset($profiledinas) ? $profiledinas->nama_website : '' }}" >
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Profile Dinas Disdukcapil</label>
                        <textarea name="tentang" id="tentang" class="form-control" rows="3">{{ isset($profiledinas) ? $profiledinas->tentang : '' }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ isset($profiledinas) ? $profiledinas->email : '' }}" >
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="2">{{ isset($profiledinas) ? $profiledinas->alamat : '' }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">No Telephone</label>
                        <input type="text" name="no_telepon" id="no_telepon" class="form-control"  maxlength="12" value="{{ isset($profiledinas) ? $profiledinas->no_telepon : '' }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">No Whatsapp</label>
                        <input type="text" name="no_whatsapp" id="no_whatsapp" class="form-control" maxlength="12" value="{{ isset($profiledinas) ? $profiledinas->no_whatsapp : '' }}">
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Twitter</label>
                        <input type="text" name="twitter" id="twitter" class="form-control" value="{{ isset($profiledinas) ? $profiledinas->twitter : '' }}" >
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Facebook</label>
                        <input type="text" name="facebook" id="facebook" class="form-control" value="{{ isset($profiledinas) ? $profiledinas->facebook : '' }}" >
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Youtube</label>
                        <input type="text" name="youtube" id="youtube" class="form-control" value="{{ isset($profiledinas) ? $profiledinas->youtube : '' }}" >
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Tiktok</label>
                        <input type="text" name="tiktok" id="tiktok" class="form-control" value="{{ isset($profiledinas) ? $profiledinas->tiktok : '' }}" >
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('profiledinas.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>

        document.getElementById('postForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    App.redirect(response.redirect);
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });
    </script>
@endpush
