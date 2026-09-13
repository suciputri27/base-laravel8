@extends('layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Role</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari role..." style="width: 240px; margin-right: 8px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="openRoleModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="roleScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Permissions</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="roleTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="roleForm" action="{{ route('roles.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="roleMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="roleModalTitle">Tambah Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="roleName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Permissions</label>
                            <select name="permissions[]" id="rolePermissions" class="form-control" multiple style="height: 240px;">
                                @foreach($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
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
        function roleRow(item) {
            var permissions = (item.permissions || []).join(', ');

            return '<tr>' +
                '<td><span class="badge badge-secondary">' + App.escapeHtml(item.name) + '</span></td>' +
                '<td>' + App.escapeHtml(permissions || '-') + '</td>' +
                '<td>' + App.escapeHtml(item.created_at || '-') + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editRole(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.name) + '" data-permissions="' + App.escapeHtml(JSON.stringify(item.permissions || [])) + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteRole(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openRoleModal() {
            document.getElementById('roleMethod').value = 'POST';
            document.getElementById('roleForm').action = '{{ route('roles.store') }}';
            document.getElementById('roleModalTitle').textContent = 'Tambah Role';
            document.getElementById('roleName').value = '';
            document.querySelectorAll('#rolePermissions option').forEach(function (option) {
                option.selected = false;
            });
            new bootstrap.Modal(document.getElementById('roleModal')).show();
        }

        function editRole(encryptedId, button) {
            var permissions = JSON.parse(button.getAttribute('data-permissions') || '[]');

            document.getElementById('roleMethod').value = 'PUT';
            document.getElementById('roleForm').action = '{{ url('roles') }}/' + encryptedId;
            document.getElementById('roleModalTitle').textContent = 'Edit Role';
            document.getElementById('roleName').value = button.getAttribute('data-name');
            document.querySelectorAll('#rolePermissions option').forEach(function (option) {
                option.selected = permissions.indexOf(option.value) !== -1;
            });
            new bootstrap.Modal(document.getElementById('roleModal')).show();
        }

        function deleteRole(encryptedId) {
            App.confirm({
                title: 'Hapus Role',
                text: 'Role akan dihapus secara permanen.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('roles') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        roleTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            roleTable.search(this.value);
        });

        document.getElementById('roleForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('roleModal')).hide();
                    App.alert('success', response.message);
                    roleTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var roleTable = App.infiniteScroll({
            container: '#roleScroll',
            body: '#roleTableBody',
            endpoint: '{{ route('roles.paginate') }}',
            pageSize: 10,
            rowRenderer: roleRow
        });

        roleTable.load(true);
    </script>
@endpush
