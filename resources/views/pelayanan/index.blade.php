@extends('layouts.app')

@section('title', 'Pelayanan')
@section('page-title', 'Pelayanan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Pelayanan</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari pelayanan..." style="width: 240px; margin-right: 8px;">
                @if(auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('pelayanan.trashed') }}" class="btn btn-outline-secondary btn-sm" style="margin-right: 8px;">
                        <i class="fas fa-trash-restore"></i> Sampah
                    </a>
                @endif
                <button type="button" class="btn btn-primary btn-sm" onclick="openPelayananModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="PelayananScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Pelayanan</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="PelayananTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="PelayananModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="PelayananForm" action="{{ route('pelayanan.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="PelayananMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="PelayananModalTitle">Tambah Pelayanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama_pelayanan" id="PelayananName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" id="PelayananDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="PelayananIsActive" class="form-control">
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
        function PelayananRow(item) {
            var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';

            return '<tr>' +
                '<td>' + App.escapeHtml(item.nama_pelayanan) + '</td>' +
                '<td>' + App.escapeHtml(item.deskripsi || '-') + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editPelayanan(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.nama_pelayanan) + '" data-description="' + App.escapeHtml(item.deskripsi || '') + '" data-active="' + (item.is_active ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePelayanan(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openPelayananModal() {
            document.getElementById('PelayananMethod').value = 'POST';
            document.getElementById('PelayananForm').action = '{{ route('pelayanan.store') }}';
            document.getElementById('PelayananModalTitle').textContent = 'Tambah Kategori';
            document.getElementById('PelayananName').value = '';
            document.getElementById('PelayananDescription').value = '';
            document.getElementById('PelayananIsActive').value = '1';
            new bootstrap.Modal(document.getElementById('PelayananModal')).show();
        }

        function editPelayanan(encryptedId, button) {
            document.getElementById('PelayananMethod').value = 'PUT';
            document.getElementById('PelayananForm').action = '{{ url('pelayanan') }}/' + encryptedId;
            document.getElementById('PelayananModalTitle').textContent = 'Edit Kategori';
            document.getElementById('PelayananName').value = button.getAttribute('data-name');
            document.getElementById('PelayananDescription').value = button.getAttribute('data-description');
            document.getElementById('PelayananIsActive').value = button.getAttribute('data-active');
            new bootstrap.Modal(document.getElementById('PelayananModal')).show();
        }

        function deletePelayanan(encryptedId) {
            App.confirm({
                title: 'Hapus Kategori',
                text: 'Data akan dipindahkan ke sampah.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('pelayanan') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        PelayananTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            PelayananTable.search(this.value);
        });

        document.getElementById('PelayananForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('PelayananModal')).hide();
                    App.alert('success', response.message);
                    PelayananTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var PelayananTable = App.infiniteScroll({
            container: '#PelayananScroll',
            body: '#PelayananTableBody',
            endpoint: '{{ route('pelayanan.paginate') }}',
            pageSize: 10,
            rowRenderer: PelayananRow
        });

        PelayananTable.load(true);
    </script>
@endpush
