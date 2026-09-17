@extends('layouts.app')

@section('title', 'Persyaratan')
@section('page-title', 'Persyaratan')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Data Persyaratan</h5>
        <div class="d-flex align-items-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Persyaratan..." style="width: 240px; margin-right: 8px;">
            @if(auth()->user()->hasRole('Super Admin'))
            <a href="{{ route('persyaratan.trashed') }}" class="btn btn-outline-secondary btn-sm" style="margin-right: 8px;">
                <i class="fas fa-trash-restore"></i> Sampah
            </a>
            @endif
            <button type="button" class="btn btn-primary btn-sm" onclick="openPersyaratanModal()">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-scroll" id="PersyaratanScroll">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Persyaratan</th>
                        <th>Cek Dokumen</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="PersyaratanTableBody"></tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="PersyaratanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="PersyaratanForm" action="{{ route('persyaratan.store') }}" method="POST" novalidate>
                @csrf
                <input type="hidden" name="_method" id="PersyaratanMethod" value="POST">

                <div class="modal-header">
                    <h5 class="modal-title" id="PersyaratanModalTitle">Tambah Persyaratan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama_persyaratan" id="PersyaratanName" class="form-control" required>
                    </div>
                    <div class="form-group mt-3">
                        <label class="form-label">Butuh Upload Dokumen?</label>
                        <select name="cekdokumen" id="PersyaratanButuhDokumen" class="form-control">
                            <option value="1">Ya, perlu upload template/dokumen</option>
                            <option value="0">Tidak</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="PersyaratanIsActive" class="form-control">
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
    function PersyaratanRow(item) {
        var status = item.is_active ?
            '<span class="badge badge-success">Aktif</span>' :
            '<span class="badge badge-danger">Nonaktif</span>';

        var cek = item.cekdokumen ?
            '<span class="badge badge-warning">Butuh Dokumen</span>' :
            '<span class="badge badge-secondary">Tidak Butuh Dokumen</span>';

        return '<tr>' +
            '<td>' + App.escapeHtml(item.nama_persyaratan) + '</td>' +
            '<td>' + cek + '</td>' +
            '<td>' + status + '</td>' +
            '<td>' +
            '<button type="button" class="btn btn-secondary btn-sm" onclick="editPersyaratan(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.nama_persyaratan) + '" data-description="' + '" data-active="' + (item.is_active ? '1' : '0') + '" data-cekdokumen="' + (item.cekdokumen ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
            '<button type="button" class="btn btn-danger btn-sm" onclick="deletePersyaratan(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
            '</td>' +
            '</tr>';
    }

    function openPersyaratanModal() {
        document.getElementById('PersyaratanMethod').value = 'POST';
        document.getElementById('PersyaratanForm').action = '{{ route('persyaratan.store') }}';
        document.getElementById('PersyaratanModalTitle').textContent = 'Tambah Kategori';
        document.getElementById('PersyaratanName').value = '';
        document.getElementById('PersyaratanButuhDokumen').value = '0';
        document.getElementById('PersyaratanIsActive').value = '1';
        new bootstrap.Modal(document.getElementById('PersyaratanModal')).show();
    }

    function editPersyaratan(encryptedId, button) {
        document.getElementById('PersyaratanMethod').value = 'PUT';
        document.getElementById('PersyaratanForm').action = '{{ url('admin/persyaratan') }}/' + encryptedId;
        document.getElementById('PersyaratanModalTitle').textContent = 'Edit Kategori';
        document.getElementById('PersyaratanName').value = button.getAttribute('data-name');
        document.getElementById('PersyaratanButuhDokumen').value = button.getAttribute('data-cekdokumen');
        document.getElementById('PersyaratanIsActive').value = button.getAttribute('data-active');
        new bootstrap.Modal(document.getElementById('PersyaratanModal')).show();
    }

    function deletePersyaratan(encryptedId) {
        App.confirm({
            title: 'Hapus Kategori',
            text: 'Data akan dipindahkan ke sampah.',
            confirmButtonText: 'Ya, hapus'
        }).then(function(result) {
            if (!result.isConfirmed) {
                return;
            }

            App.submitData({
                url: '{{ url('admin/persyaratan') }}/' + encryptedId,
                method: 'DELETE',
                onSuccess: function(response) {
                    App.alert('success', response.message);
                    PersyaratanTable.reset();
                }
            });
        });
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        PersyaratanTable.search(this.value);
    });

    document.getElementById('PersyaratanForm').addEventListener('submit', function(event) {
        event.preventDefault();

        App.submit(this, {
            onSuccess: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('PersyaratanModal')).hide();
                App.alert('success', response.message);
                PersyaratanTable.reset();
            },
            onError: function(payload) {
                App.alert('danger', payload.message || 'Terjadi kesalahan.');
            }
        });
    });

    var PersyaratanTable = App.infiniteScroll({
        container: '#PersyaratanScroll',
        body: '#PersyaratanTableBody',
        endpoint: '{{ route('persyaratan.paginate') }}',
        pageSize: 10,
        rowRenderer: PersyaratanRow
    });

    PersyaratanTable.load(true);
</script>
@endpush