@extends('layouts.app')

@section('title', 'Permission Management')
@section('page-title', 'Permission Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Permission</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari permission..." style="width: 240px; margin-right: 8px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="openPermissionModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="permissionScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Guard</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="permissionTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="permissionForm" action="{{ route('permissions.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="permissionMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="permissionModalTitle">Tambah Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="permissionName" class="form-control" required>
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
        function permissionRow(item) {
            return '<tr>' +
                '<td><span class="badge badge-secondary">' + App.escapeHtml(item.name) + '</span></td>' +
                '<td>' + App.escapeHtml(item.guard_name) + '</td>' +
                '<td>' + App.escapeHtml(item.created_at || '-') + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editPermission(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.name) + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePermission(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openPermissionModal() {
            document.getElementById('permissionMethod').value = 'POST';
            document.getElementById('permissionForm').action = '{{ route('permissions.store') }}';
            document.getElementById('permissionModalTitle').textContent = 'Tambah Permission';
            document.getElementById('permissionName').value = '';
            new bootstrap.Modal(document.getElementById('permissionModal')).show();
        }

        function editPermission(encryptedId, button) {
            document.getElementById('permissionMethod').value = 'PUT';
            document.getElementById('permissionForm').action = '{{ url('permissions') }}/' + encryptedId;
            document.getElementById('permissionModalTitle').textContent = 'Edit Permission';
            document.getElementById('permissionName').value = button.getAttribute('data-name');
            new bootstrap.Modal(document.getElementById('permissionModal')).show();
        }

        function deletePermission(encryptedId) {
            App.confirm({
                title: 'Hapus Permission',
                text: 'Permission akan dihapus secara permanen.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('permissions') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        permissionTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            permissionTable.search(this.value);
        });

        document.getElementById('permissionForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('permissionModal')).hide();
                    App.alert('success', response.message);
                    permissionTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var permissionTable = App.infiniteScroll({
            container: '#permissionScroll',
            body: '#permissionTableBody',
            endpoint: '{{ route('permissions.paginate') }}',
            pageSize: 10,
            rowRenderer: permissionRow
        });

        permissionTable.load(true);
    </script>
@endpush
