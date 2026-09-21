@extends('layouts.app')

@section('title', 'Publikasi')
@section('page-title', 'Publikasi')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Publikasi</h5>
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Publikasi..." style="width: 240px; margin-right: 8px;">
            <button type="button" class="btn btn-primary btn-sm" onclick="openPublikasiModal()">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-scroll" id="publikasiScroll">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Jenis Dokumen</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th>Berkas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="publikasiTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="PublikasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="publikasiForm" action="{{ route('publikasi.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="_method" id="publikasiMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="publikasiModalTitle">Tambah Publikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="form-group mt-3">
                            <label class="form-label">Jenis Dokumen</label>
                            <select name="jenis_dokumen" id="publikasiJenis" class="form-control">
                                <option value="1">Maklumat Pelayanan</option>
                                <option value="2">Standar Pelayanan Publik</option>
                                <option value="3">SOP</option>
                                <option value="4">Alur Pengaduan</option>
                                <option value="5">Publikasi</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Judul</label>
                            <input type="text" name="judul" id="publikasiName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="publikasiDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Upload Publikasi</label>
                            <div class="file-dropzone" id="berkasDropzone">
                                <input type="file" name="berkas" id="berkasInput" accept=".pdf,.jpg,.jpeg,.png" hidden>
                                <div class="file-dropzone-inner" id="berkasDropzoneInner">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                    <p>Seret gambar ke sini atau klik untuk pilih</p>
                                    <span>PDF, JPG, PNG, maksimal 2 MB</span>
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
                        <select name="is_active" id="publikasiIsActive" class="form-control">
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
    function publikasiRow(item) {
        var rowNumber = document.querySelectorAll('#publikasiTableBody tr').length + 1;
        var berkas = App.renderThumbnail(item.berkas_url);

        var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';
        
        var badgeMap = {
                1: '<span class="badge badge-success">Maklumat Pelayanan</span>',
                2: '<span class="badge badge-primary">Standar Pelayanan Publik</span>',
                3: '<span class="badge badge-warning">SOP</span>',
                4: '<span class="badge badge-info">Alur Pengaduan</span>',
                5: '<span class="badge badge-secondary">Publikasi</span>'
            };

        var jenis = badgeMap[item.jenis_dokumen] || '<span class="badge badge-dark">Tidak Diketahui</span>';

        return '<tr>' +
            '<td>' + rowNumber + '</td>' +
            '<td>' + jenis + '</td>' +
            '<td>' + App.escapeHtml(item.judul) + '</td>' +
            '<td>' + App.escapeHtml(item.deskripsi || '-') + '</td>' +
            '<td>' + berkas + '</td>' +
            '<td>' + status + '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-secondary btn-sm" onclick="editPublikasi(\'' + item.encrypted_id + '\', this)" data-judul="' + App.escapeHtml(item.judul) + '" data-deskripsi="' + App.escapeHtml(item.deskripsi || '') + '" data-berkas-url="' + App.escapeHtml(item.berkas_url || '') + '" data-active="' + (item.is_active ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="deletePublikasi(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
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

    function resetBerkasDropzone() {
        berkasInput.value = '';
        berkasPreviewImage.src = '';
        berkasPreviewImage.classList.remove('d-none');

        var pdfInfo = document.getElementById('berkasPdfInfo');
        if (pdfInfo) {
            pdfInfo.classList.add('d-none');
        }

        berkasDropzoneInner.classList.remove('d-none');
        berkasPreview.classList.add('d-none');
    }

    berkasRemove.addEventListener('click', function (e) {
        e.stopPropagation();
        berkasInput.value = '';
        berkasDropzoneInner.classList.remove('d-none');
        berkasPreview.classList.add('d-none');
        berkasPreviewImage.src = '';
    });

    function showBerkasPreview(file) {
        if (file.type === 'application/pdf') {
            // Untuk PDF, tampilkan ikon + nama file, bukan gambar
            berkasPreviewImage.classList.add('d-none');

            var pdfInfo = document.getElementById('berkasPdfInfo');
            if (!pdfInfo) {
                pdfInfo = document.createElement('div');
                pdfInfo.id = 'berkasPdfInfo';
                pdfInfo.className = 'pdf-file-info';
                berkasPreview.insertBefore(pdfInfo, berkasRemove);
            }
            pdfInfo.innerHTML = '<i class="fas fa-file-pdf"></i> ' + App.escapeHtml(file.name);
            pdfInfo.classList.remove('d-none');

            berkasDropzoneInner.classList.add('d-none');
            berkasPreview.classList.remove('d-none');
            return;
        }

        // Untuk gambar, tetap pakai preview seperti biasa
        var pdfInfo = document.getElementById('berkasPdfInfo');
        if (pdfInfo) {
            pdfInfo.classList.add('d-none');
        }
        berkasPreviewImage.classList.remove('d-none');

        var reader = new FileReader();
        reader.onload = function (e) {
            berkasPreviewImage.src = e.target.result;
            berkasDropzoneInner.classList.add('d-none');
            berkasPreview.classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
    // ==== end dropzone ====

    function openPublikasiModal() {
        document.getElementById('publikasiMethod').value = 'POST';
        document.getElementById('publikasiForm').action = '{{ route('publikasi.store') }}';
        document.getElementById('publikasiModalTitle').textContent = 'Tambah Publikasi';
        document.getElementById('publikasiJenis').value = '';
        document.getElementById('publikasiName').value = '';
        document.getElementById('publikasiDescription').value = '';
        document.getElementById('publikasiIsActive').value = '1';
        resetBerkasDropzone();
        new bootstrap.Modal(document.getElementById('PublikasiModal')).show();
    }

    function editPublikasi(encryptedId, button) {
        document.getElementById('publikasiMethod').value = 'PUT';
        document.getElementById('publikasiForm').action = '{{ url('admin/publikasi') }}/' + encryptedId;
        document.getElementById('publikasiModalTitle').textContent = 'Edit Publikasi';
        document.getElementById('publikasiJenis').value =button.getAttribute('data-jenis');
        document.getElementById('publikasiName').value = button.getAttribute('data-judul');
        document.getElementById('publikasiDescription').value = button.getAttribute('data-deskripsi');
        document.getElementById('publikasiIsActive').value = button.getAttribute('data-active');

        resetBerkasDropzone();
        var berkasUrl = button.getAttribute('data-berkas-url');
        if (berkasUrl) {
            var isPdf = berkasUrl.toLowerCase().endsWith('.pdf');

            if (isPdf) {
                berkasPreviewImage.classList.add('d-none');

                var pdfInfo = document.getElementById('berkasPdfInfo');
                if (!pdfInfo) {
                    pdfInfo = document.createElement('div');
                    pdfInfo.id = 'berkasPdfInfo';
                    pdfInfo.className = 'pdf-file-info';
                    berkasPreview.insertBefore(pdfInfo, berkasRemove);
                }
                pdfInfo.innerHTML = '<i class="fas fa-file-pdf"></i> Berkas saat ini (PDF)';
                pdfInfo.classList.remove('d-none');
            } else {
                berkasPreviewImage.classList.remove('d-none');
                berkasPreviewImage.src = berkasUrl;
            }

            berkasDropzoneInner.classList.add('d-none');
            berkasPreview.classList.remove('d-none');
        }

        new bootstrap.Modal(document.getElementById('PublikasiModal')).show();
    }

    function deletePublikasi(encryptedId) {
        App.confirm({
            title: 'Hapus Publikasi',
            confirmButtonText: 'Ya, hapus'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            App.submitData({
                url: '{{ url('admin/publikasi') }}/' + encryptedId,
                method: 'DELETE',
                onSuccess: function(response) {
                    App.alert('success', response.message);
                    publikasiTable.reset();
                }
            });
        });
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        publikasiTable.search(this.value);
    });

    document.getElementById('publikasiForm').addEventListener('submit', function(event) {
        event.preventDefault();

        App.submit(this, {
            onSuccess: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('PublikasiModal')).hide();
                App.alert('success', response.message);
                publikasiTable.reset();
            },
            onError: function(payload) {
                App.alert('danger', payload.message || 'Terjadi kesalahan.');
            }
        });
    });

    var publikasiTable = App.infiniteScroll({
        container: '#publikasiScroll',
        body: '#publikasiTableBody',
        endpoint: '{{ route('publikasi.paginate') }}',
        pageSize: 10,
        rowRenderer: publikasiRow
    });

    publikasiTable.load(true);
</script>
@endpush