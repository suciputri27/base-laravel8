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
            <form id="StrukturForm" action="{{ route('struktur_organisasi.store') }}" method="POST" novalidate>
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
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" id="StatusIsActive" class="form-control">
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
        var thumbnail = item.thumbnail_url
                ? '<img src="' + App.escapeHtml(item.thumbnail_url) + '" alt="" class="post-thumb">'
                : '<div class="post-thumb post-thumb-empty"><i class="fas fa-image"></i></div>';

        var status = item.status ?
            '<span class="badge badge-success">Aktif</span>' :
            '<span class="badge badge-danger">Nonaktif</span>';

        return '<tr>' +
            '<td>' + thumbnail + '</td>' +
            '<td>' + status + '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-secondary btn-sm" onclick="editStruktur(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.name) + '" data-description="' + App.escapeHtml(item.description || '') + '" data-active="' + (item.is_active ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="deleteStruktur(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
            '</td>' +
            '</tr>';
    }

    function openStrukturModal() {
        document.getElementById('StrukturMethod').value = 'POST';
        document.getElementById('StrukturForm').action = '{{ route('struktur_organisasi.store') }}';
        document.getElementById('StrukturModalTitle').textContent = 'Tambah Struktur Organisasi';
        document.getElementById('berkasInput').value = '';
        document.getElementById('StatusIsActive').value = '1';
        new bootstrap.Modal(document.getElementById('StrukturModal')).show();
    }

    function editStruktur(encryptedId, button) {
        document.getElementById('StrukturMethod').value = 'PUT';
        document.getElementById('StrukturForm').action = '{{ url('struktur_organisasi') }}/' + encryptedId;
        document.getElementById('StrukturModalTitle').textContent = 'Edit Struktur Organisasi';
        document.getElementById('berkasInput').value = button.getAttribute('data-berkas');
        document.getElementById('StrukturIsActive').value = button.getAttribute('data-active');
        new bootstrap.Modal(document.getElementById('StrukturModal')).show();
    }

    function deleteStruktur(encryptedId) {
        App.confirm({
            title: 'Hapus Kategori',
            confirmButtonText: 'Ya, hapus'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            App.submitData({
                url: '{{ url('struktur_organisasi') }}/' + encryptedId,
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
        container: '#StrukturScroll',
        body: '#StrukturTableBody',
        endpoint: '{{ route('struktur_organisasi.paginate') }}',
        pageSize: 10,
        rowRenderer: StrukturRow
    });

    StrukturTable.load(true);
</script>
@endpush