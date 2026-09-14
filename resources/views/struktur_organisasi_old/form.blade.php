@php
    $existingThumbnail = isset($post) && $post->thumbnail ? storage_url($post->thumbnail) : '';
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($post) ? 'Edit Struktur Organisasi' : 'Tambah Struktur Organisasi' }}</h5>
    </div>
    <div class="card-body">
        <form id="strukturForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @if($formMethod === 'PUT')
                <input type="hidden" name="_method" value="PUT">
            @endif

            <div class="row g-3">

                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Upload Struktur Organisasi</label>
                        <div class="file-dropzone" id="thumbnailDropzone">
                            <input type="file" name="thumbnail" id="thumbnailInput" accept="image/*" hidden>
                            <div class="file-dropzone-inner" id="thumbnailDropzoneInner">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p>Seret gambar ke sini atau klik untuk pilih</p>
                                <span>JPG, PNG, WEBP, GIF maksimal 2 MB</span>
                            </div>
                            <div class="file-preview d-none" id="thumbnailPreview">
                                <img id="thumbnailPreviewImage" src="" alt="">
                                <button type="button" id="thumbnailRemove" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
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

        var thumbnailInput = document.getElementById('thumbnailInput');
        var dropzone = document.getElementById('thumbnailDropzone');
        var dropzoneInner = document.getElementById('thumbnailDropzoneInner');
        var preview = document.getElementById('thumbnailPreview');
        var previewImage = document.getElementById('thumbnailPreviewImage');
        var removeBtn = document.getElementById('thumbnailRemove');

        var existingThumbnail = @json($existingThumbnail);

        if (existingThumbnail) {
            dropzoneInner.classList.add('d-none');
            preview.classList.remove('d-none');
            previewImage.src = existingThumbnail;
        }

        dropzone.addEventListener('click', function () {
            thumbnailInput.click();
        });

        thumbnailInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                showPreview(this.files[0]);
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
                thumbnailInput.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files[0]);
            }
        });

        removeBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            thumbnailInput.value = '';
            dropzoneInner.classList.remove('d-none');
            preview.classList.add('d-none');
            previewImage.src = '';
        });

        function showPreview(file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                previewImage.src = e.target.result;
                dropzoneInner.classList.add('d-none');
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }

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
