@extends('layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data User</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari nama atau email..." style="width: 240px; margin-right: 8px;">
                @if(auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('users.trashed') }}" class="btn btn-outline-secondary btn-sm" style="margin-right: 8px;">
                        <i class="fas fa-trash-restore"></i> Sampah
                    </a>
                @endif
                <button type="button" class="btn btn-primary btn-sm" onclick="openUserModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="userScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm" action="{{ route('users.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="userMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="userModalTitle">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="userName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="userEmail" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <select name="role" id="userRole" class="form-control">
                                <option value="">Tanpa Role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" id="userPassword" class="form-control">
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
        function userRow(item) {
            var role = item.role_name ? item.role_name : 'Tanpa Role';
            var created = item.created_at ? item.created_at : '-';

            return '<tr>' +
                '<td>' + App.escapeHtml(item.name) + '</td>' +
                '<td>' + App.escapeHtml(item.email) + '</td>' +
                '<td><span class="badge badge-secondary">' + App.escapeHtml(role) + '</span></td>' +
                '<td>' + App.escapeHtml(created) + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editUser(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.name) + '" data-email="' + App.escapeHtml(item.email) + '" data-role="' + App.escapeHtml(role) + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteUser(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openUserModal() {
            document.getElementById('userMethod').value = 'POST';
            document.getElementById('userForm').action = '{{ route('users.store') }}';
            document.getElementById('userModalTitle').textContent = 'Tambah User';
            document.getElementById('userName').value = '';
            document.getElementById('userEmail').value = '';
            document.getElementById('userRole').value = '';
            document.getElementById('userPassword').value = '';
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        function editUser(encryptedId, button) {
            document.getElementById('userMethod').value = 'PUT';
            document.getElementById('userForm').action = '{{ url('users') }}/' + encryptedId;
            document.getElementById('userModalTitle').textContent = 'Edit User';
            document.getElementById('userName').value = button.getAttribute('data-name');
            document.getElementById('userEmail').value = button.getAttribute('data-email');
            document.getElementById('userRole').value = button.getAttribute('data-role') === 'Tanpa Role' ? '' : button.getAttribute('data-role');
            document.getElementById('userPassword').value = '';
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        function deleteUser(encryptedId) {
            App.confirm({
                title: 'Hapus User',
                text: 'Data akan dipindahkan ke sampah.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('users') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        userTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            userTable.search(this.value);
        });

        document.getElementById('userForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
                    App.alert('success', response.message);
                    userTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var userTable = App.infiniteScroll({
            container: '#userScroll',
            body: '#userTableBody',
            endpoint: '{{ route('users.paginate') }}',
            pageSize: 10,
            rowRenderer: userRow
        });

        userTable.load(true);
    </script>
@endpush
