@extends('layouts.app')

@section('title', 'Struktur Organisasi')
@section('page-title', 'Struktur Organisasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Struktur Organisasi</h5>
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari struktur..." style="width: 240px; margin-right: 8px;">
            <button type="button" class="btn btn-primary btn-sm" onclick="openStrukturModal()">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-scroll" id="strukturScroll">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Struktur</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="strukturTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="StrukturModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="StrukturForm" action="{{ route('struktur_organisasi.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="_method" id="StrukturMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="StrukturModalTitle">Tambah Struktur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group mb-0">
                            <label class="form-label">Upload Struktur Organisasi</label>
                            <div class="file-dropzone" id="berkasDropzone">
                                <input type="file" name="berkas" id="berkasInput" accept="image/*" hidden>
                                <div class="file-dropzone-inner" id="berkasDropzoneInner">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                    <p>Seret gambar ke sini atau klik untuk pilih</p>
                                    <span>JPG, PNG, WEBP, GIF maksimal 2 MB</span>
                                </div>
                                <div class="file-preview d-none" id="berkasPreview">
                                    <img id="berkasPreviewImage" src="" alt="">
                                    <button type="button" id="berkasRemove" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="StrukturIsActive" class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function StrukturRow(item) {
        var rowNumber = document.querySelectorAll('#strukturTableBody tr').length + 1;
        var berkas = item.berkas_url
                ? '<img src="' + App.escapeHtml(item.berkas_url) + '" alt="" class="post-thumb">'
                : '<div class="post-thumb post-thumb-empty"><i class="fas fa-image"></i></div>';

        var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';

        return '<tr>' +
            '<td>' + rowNumber + '</td>' +
            '<td>' + berkas + '</td>' +
            '<td>' + status + '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-secondary btn-sm" onclick="editStruktur(\'' + item.encrypted_id + '\', this)" data-berkas-url="' + App.escapeHtml(item.berkas_url || '') + '" data-active="' + (item.is_active ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="deleteStruktur(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
            '</td>' +
            '</tr>';
    }

    // ==== Dropzone berkas: klik, drag & drop, preview ====
    var berkasInput = document.getElementById('berkasInput');
    var berkasDropzone = document.getElementById('berkasDropzone');
    var berkasDropzoneInner = document.getElementById('berkasDropzoneInner');
    var berkasPreview = document.getElementById('berkasPreview');
    var berkasPreviewImage = document.getElementById('berkasPreviewImage');
    var berkasRemove = document.getElementById('berkasRemove');

    berkasDropzone.addEventListener('click', function () {
        berkasInput.click();
    });

    berkasInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            showBerkasPreview(this.files[0]);
        }
    });

    ['dragover', 'dragenter'].forEach(function (eventName) {
        berkasDropzone.addEventListener(eventName, function (e) {
            e.preventDefault();
            berkasDropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(function (eventName) {
        berkasDropzone.addEventListener(eventName, function (e) {
            e.preventDefault();
            berkasDropzone.classList.remove('dragover');
        });
    });

    berkasDropzone.addEventListener('drop', function (e) {
        if (e.dataTransfer.files && e.dataTransfer.files[0]) {
            berkasInput.files = e.dataTransfer.files;
            showBerkasPreview(e.dataTransfer.files[0]);
        }
    });

    berkasRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        berkasInput.value = '';
        berkasDropzoneInner.classList.remove('d-none');
        berkasPreview.classList.add('d-none');
        berkasPreviewImage.src = '';
    });

    function showBerkasPreview(file) {
        var reader = new FileReader();
        reader.onload = function (e) {
            berkasPreviewImage.src = e.target.result;
            berkasDropzoneInner.classList.add('d-none');
            berkasPreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }

    function resetBerkasDropzone() {
        berkasInput.value = '';
        berkasPreviewImage.src = '';
        berkasDropzoneInner.classList.remove('d-none');
        berkasPreview.classList.add('d-none');
    }
    // ==== end dropzone ====

    function openStrukturModal() {
        document.getElementById('StrukturMethod').value = 'POST';
        document.getElementById('StrukturForm').action = '{{ route('struktur_organisasi.store') }}';
        document.getElementById('StrukturModalTitle').textContent = 'Tambah Struktur Organisasi';
        document.getElementById('StrukturIsActive').value = '1';
        resetBerkasDropzone();
        new bootstrap.Modal(document.getElementById('StrukturModal')).show();
    }

    function editStruktur(encryptedId, button) {
        document.getElementById('StrukturMethod').value = 'PUT';
        document.getElementById('StrukturForm').action = '{{ url('admin/struktur') }}/' + encryptedId;
        document.getElementById('StrukturModalTitle').textContent = 'Edit Struktur Organisasi';
        document.getElementById('StrukturIsActive').value = button.getAttribute('data-active');

        resetBerkasDropzone();
        var berkasUrl = button.getAttribute('data-berkas-url');
        if (berkasUrl) {
            berkasPreviewImage.src = berkasUrl;
            berkasDropzoneInner.classList.add('d-none');
            berkasPreview.classList.remove('d-none');
        }

        new bootstrap.Modal(document.getElementById('StrukturModal')).show();
    }

    function deleteStruktur(encryptedId) {
        App.confirm({
            title: 'Hapus Struktur Organisasi',
            confirmButtonText: 'Ya, hapus'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            App.submitData({
                url: '{{ url('admin/struktur') }}/' + encryptedId,
                method: 'DELETE',
                onSuccess: function(response) {
                    App.alert('success', response.message);
                    StrukturTable.reset();
                }
            });
        });
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        StrukturTable.search(this.value);
    });

    document.getElementById('StrukturForm').addEventListener('submit', function(event) {
        event.preventDefault();

        App.submit(this, {
            onSuccess: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('StrukturModal')).hide();
                App.alert('success', response.message);
                StrukturTable.reset();
            },
            onError: function(payload) {
                App.alert('danger', payload.message || 'Terjadi kesalahan.');
            }
        });
    });

    var StrukturTable = App.infiniteScroll({
        container: '#strukturScroll',
        body: '#strukturTableBody',
        endpoint: '{{ route('struktur_organisasi.paginate') }}',
        pageSize: 10,
        rowRenderer: StrukturRow
    });

    StrukturTable.load(true);
</script>
@endpush