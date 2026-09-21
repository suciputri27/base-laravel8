@php
    $existingBerkas = isset($publikasi) && $publikasi->berkas ? storage_url($publikasi->berkas) : '';
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($publikasi) ? 'Edit Publikasi' : 'Tambah Publikasi' }}</h5>
    </div>
    <div class="card-body">
        <form id="publikasiForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @if($formMethod === 'PUT')
                <input type="hidden" name="_method" value="PUT">
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Jenis Dokumen</label>
                        <select name="jenis_dokumen" id="jenis_dokumen" class="form-control">
                            <option value="1" {{ isset($publikasi) && $publikasi->jenis_dokumen == 1 ? 'selected' : '' }}>Maklumat Pelayanan</option>
                            <option value="2" {{ isset($publikasi) && $publikasi->jenis_dokumen == 2 ? 'selected' : '' }}>Standar Pelayanan Publik</option>
                            <option value="3" {{ isset($publikasi) && $publikasi->jenis_dokumen == 3 ? 'selected' : '' }}>SOP</option>
                            <option value="4" {{ isset($publikasi) && $publikasi->jenis_dokumen == 4 ? 'selected' : '' }}>Alur Pengaduan</option>
                            <option value="5" {{ isset($publikasi) && $publikasi->jenis_dokumen == 5 ? 'selected' : '' }}>Publikasi</option>
                        </select>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control" value="{{ isset($publikasi) ? $publikasi->judul : '' }}" required>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3">{{ isset($publikasi) ? $publikasi->deskripsi : '' }}</textarea>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Berkas</label>
                        <div class="file-dropzone" id="BerkasDropzone">
                            <input type="file" name="berkas" id="BerkasInput" accept=".pdf,.jpg,.jpeg,.png" hidden>
                            <div class="file-dropzone-inner" id="BerkasDropzoneInner">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p>Seret gambar ke sini atau klik untuk pilih</p>
                                <span>PDF, JPG, PNG, maksimal 2 MB</span>
                            </div>
                            <div class="file-preview d-none" id="BerkasPreview">
                                <img id="BerkasPreviewImage" src="" alt="">
                                <button type="button" id="BerkasRemove" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="publikasiIsActive" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-3">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#jenis_dokumen').select2({
                width: '100%',
                placeholder: 'Pilih Jenis Dokumen'
            });
        });

        var berkasInput = document.getElementById('BerkasInput');
        var dropzone = document.getElementById('BerkasDropzone');
        var dropzoneInner = document.getElementById('BerkasDropzoneInner');
        var preview = document.getElementById('BerkasPreview');
        var previewBerkas = document.getElementById('BerkasPreviewImage');
        var removeBtn = document.getElementById('BerkasRemove');

        var existingBerkas = @json($existingBerkas);

        if (existingBerkas) {
            dropzoneInner.classList.add('d-none');
            preview.classList.remove('d-none');
            previewBerkas.src = existingBerkas;
        }

        dropzone.addEventListener('click', function () {
            berkasInput.click();
        });

        berkasInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                showBerkasPreview(this.files[0]);
            }
        });

        ['dragover', 'dragenter'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(function (eventName) {
            dropzone.addEventListener(eventName, function (e) {
                e.preventDefault();
                dropzone.classList.remove('dragover');
            });
        });

        dropzone.addEventListener('drop', function (e) {
            if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                berkasInput.files = e.dataTransfer.files;
                showBerkasPreview(e.dataTransfer.files[0]);
            }
        });

        removeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            berkasInput.value = '';
            dropzoneInner.classList.remove('d-none');
            preview.classList.add('d-none');
            previewBerkas.src = '';

            var pdfInfo = document.getElementById('berkasPdfInfo');
            if (pdfInfo) {
                pdfInfo.classList.add('d-none');
            }
        });

        function showBerkasPreview(file) {
            if (file.type === 'application/pdf') {
                // Untuk PDF, tampilkan ikon + nama file, bukan gambar
                previewBerkas.classList.add('d-none');

                var pdfInfo = document.getElementById('berkasPdfInfo');
                if (!pdfInfo) {
                    pdfInfo = document.createElement('div');
                    pdfInfo.id = 'berkasPdfInfo';
                    pdfInfo.className = 'pdf-file-info';
                    preview.insertBefore(pdfInfo, removeBtn);
                }
                pdfInfo.innerHTML = '<i class="fas fa-file-pdf"></i> ' + file.name;
                pdfInfo.classList.remove('d-none');

                dropzoneInner.classList.add('d-none');
                preview.classList.remove('d-none');
                return;
            }

            // Untuk gambar, tetap pakai preview seperti biasa
            var pdfInfo = document.getElementById('berkasPdfInfo');
            if (pdfInfo) {
                pdfInfo.classList.add('d-none');
            }
            previewBerkas.classList.remove('d-none');

            var reader = new FileReader();
            reader.onload = function (e) {
                previewBerkas.src = e.target.result;
                dropzoneInner.classList.add('d-none');
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }

        document.getElementById('publikasiForm').addEventListener('submit', function (event) {
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
