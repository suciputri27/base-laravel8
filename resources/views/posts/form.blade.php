@php
    $selectedCategory = isset($post) && $post->category_id ? id_encode((int) $post->category_id) : ''; 
    $existingBerkas = isset($post) ? $post->berkas : collect();
@endphp

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">{{ isset($post) ? 'Edit Informasi' : 'Tambah Informasi' }}</h5>
    </div>
    <div class="card-body">
        <form id="postForm" action="{{ $formAction }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            @if($formMethod === 'PUT')
                <input type="hidden" name="_method" value="PUT">
            @endif

            <div class="row g-3">
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Judul</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ isset($post) ? $post->title : '' }}" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->encrypted_id }}" {{ $selectedCategory === $category->encrypted_id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group mb-0">
                        <label class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="draft" {{ (isset($post) && $post->status === 'draft') || !isset($post) ? 'selected' : '' }}>Draf</option>
                            <option value="published" {{ isset($post) && $post->status === 'published' ? 'selected' : '' }}>Terbit</option>
                        </select>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Thumbnail (bisa lebih dari satu)</label>
                        <div class="file-dropzone" id="berkasDropzone">
                            <input type="file" name="berkas[]" id="berkasInput" accept="image/*" multiple hidden>
                            <div class="file-dropzone-inner" id="berkasDropzoneInner">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p>Seret gambar ke sini atau klik untuk pilih (boleh pilih banyak file)</p>
                                <span>JPG, PNG, WEBP, GIF maksimal 2 MB / file</span>
                            </div>
                        </div>

                        {{-- Preview file baru yang akan diupload --}}
                        <div class="file-preview-grid mt-2 d-flex flex-wrap gap-2" id="berkasPreviewGrid"></div>

                        {{-- Foto lama (mode edit) --}}
                        @if(isset($post) && $existingBerkas->count())
                            <div class="existing-preview-grid mt-2 d-flex flex-wrap gap-2" id="existingPreviewGrid">
                                @foreach($existingBerkas as $b)
                                    <div class="file-preview-item position-relative" data-id="{{ $b->encrypted_id ?? id_encode($b->id) }}">
                                        <img src="{{ storage_url($b->berkas) }}" style="width:90px;height:90px;object-fit:cover;border-radius:6px;">
                                        <button type="button" class="btn btn-danger btn-sm existing-remove-btn position-absolute top-0 end-0">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        {{-- id foto lama yang dihapus dikirim lewat hidden input ini --}}
                                    </div>
                                @endforeach
                            </div>
                            {{-- Berisi id-id berkas lama yang dihapus user, dipisah koma --}}
                            <input type="hidden" name="deleted_berkas" id="deletedBerkasInput" value="">
                        @endif
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-0">
                        <label class="form-label">Isi Berita</label>
                        <textarea name="content" id="content" class="form-control" rows="10">{{ isset($post) ? $post->content : '' }}</textarea>
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
            $('#category_id').select2({
                width: '100%',
                placeholder: 'Pilih Kategori'
            });
        });

        var berkasInput = document.getElementById('berkasInput');
        var dropzone = document.getElementById('berkasDropzone');
        var previewGrid = document.getElementById('berkasPreviewGrid');
        var deletedBerkasInput = document.getElementById('deletedBerkasInput');

        // simpan file terpilih dalam array manual, karena FileList tidak bisa diedit langsung
        var selectedFiles = [];

        dropzone.addEventListener('click', function () {
            berkasInput.click();
        });

        berkasInput.addEventListener('change', function () {
            addFiles(this.files);
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
            if (e.dataTransfer.files && e.dataTransfer.files.length) {
                addFiles(e.dataTransfer.files);
            }
        });

        function addFiles(fileList) {
            Array.from(fileList).forEach(function (file) {
                if (!file.type.startsWith('image/')) return;
                selectedFiles.push(file);
            });
            syncInputFiles();
            renderPreview();
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            syncInputFiles();
            renderPreview();
        }

        // Sinkronkan array selectedFiles ke dalam <input type="file"> pakai DataTransfer
        function syncInputFiles() {
            var dt = new DataTransfer();
            selectedFiles.forEach(function (file) {
                dt.items.add(file);
            });
            berkasInput.files = dt.files;
        }

        function renderPreview() {
            previewGrid.innerHTML = '';
            selectedFiles.forEach(function (file, index) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    var item = document.createElement('div');
                    item.className = 'file-preview-item position-relative';
                    item.innerHTML =
                        '<img src="' + e.target.result + '" style="width:90px;height:90px;object-fit:cover;border-radius:6px;">' +
                        '<button type="button" class="btn btn-danger btn-sm new-remove-btn position-absolute top-0 end-0"><i class="fas fa-trash"></i></button>';
                    item.querySelector('.new-remove-btn').addEventListener('click', function (e) {
                        e.stopPropagation();
                        removeFile(index);
                    });
                    previewGrid.appendChild(item);
                };
                reader.readAsDataURL(file);
            });
        }

        // Hapus foto lama (mode edit): tandai id-nya, submit via hidden input
        document.querySelectorAll('.existing-remove-btn').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var item = btn.closest('.file-preview-item');
                var id = item.getAttribute('data-id');

                var current = deletedBerkasInput.value ? deletedBerkasInput.value.split(',') : [];
                current.push(id);
                deletedBerkasInput.value = current.join(',');

                item.remove();
            });
        });

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
