@extends('layouts.app')

@section('title', 'Menu Management')
@section('page-title', 'Menu Management')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Menu</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari menu..." style="width: 240px; margin-right: 8px;">
                @if(auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('menus.trashed') }}" class="btn btn-outline-secondary btn-sm" style="margin-right: 8px;">
                        <i class="fas fa-trash-restore"></i> Sampah
                    </a>
                @endif
                <button type="button" class="btn btn-primary btn-sm" onclick="openMenuModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="menuScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Icon</th>
                            <th>Route atau URL</th>
                            <th>Permission</th>
                            <th>Urutan</th>
                            <th>Parent</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="menuTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="menuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="menuForm" action="{{ route('menus.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="menuMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="menuModalTitle">Tambah Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="menuName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Icon</label>
                            <input type="text" name="icon" id="menuIcon" class="form-control" placeholder="fas fa-home">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Route atau URL</label>
                            <input type="text" name="route_or_url" id="menuRoute" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Permission</label>
                            <input type="text" name="permission_name" id="menuPermission" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Urutan</label>
                            <input type="number" name="order_no" id="menuOrder" class="form-control" value="0">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Parent</label>
                            <select name="parent_id" id="menuParentId" class="form-control">
                                <option value="">Tanpa Parent</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->encrypted_id }}">{{ $menu->name }}</option>
                                    @foreach($menu->children as $child)
                                        <option value="{{ $child->encrypted_id }}">-- {{ $child->name }}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="menuIsActive" class="form-control">
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
        function menuRow(item) {
            var parent = item.parent_name ? item.parent_name : 'Tanpa Parent';
            var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';

            return '<tr>' +
                '<td>' + App.escapeHtml(item.name) + '</td>' +
                '<td><i class="' + App.escapeHtml(item.icon || '') + '"></i></td>' +
                '<td>' + App.escapeHtml(item.route_or_url || '-') + '</td>' +
                '<td>' + App.escapeHtml(item.permission_name || '-') + '</td>' +
                '<td>' + App.escapeHtml(item.order_no) + '</td>' +
                '<td>' + App.escapeHtml(parent) + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editMenu(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.name) + '" data-icon="' + App.escapeHtml(item.icon || '') + '" data-route="' + App.escapeHtml(item.route_or_url || '') + '" data-permission="' + App.escapeHtml(item.permission_name || '') + '" data-order="' + App.escapeHtml(item.order_no) + '" data-parent="' + App.escapeHtml(item.parent_id || '') + '" data-active="' + (item.is_active ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteMenu(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openMenuModal() {
            document.getElementById('menuMethod').value = 'POST';
            document.getElementById('menuForm').action = '{{ route('menus.store') }}';
            document.getElementById('menuModalTitle').textContent = 'Tambah Menu';
            document.getElementById('menuName').value = '';
            document.getElementById('menuIcon').value = '';
            document.getElementById('menuRoute').value = '';
            document.getElementById('menuPermission').value = '';
            document.getElementById('menuOrder').value = '0';
            document.getElementById('menuParentId').value = '';
            document.getElementById('menuIsActive').value = '1';
            new bootstrap.Modal(document.getElementById('menuModal')).show();
        }

        function editMenu(encryptedId, button) {
            document.getElementById('menuMethod').value = 'PUT';
            document.getElementById('menuForm').action = '{{ url('admin/menus') }}/' + encryptedId;
            document.getElementById('menuModalTitle').textContent = 'Edit Menu';
            document.getElementById('menuName').value = button.getAttribute('data-name');
            document.getElementById('menuIcon').value = button.getAttribute('data-icon');
            document.getElementById('menuRoute').value = button.getAttribute('data-route');
            document.getElementById('menuPermission').value = button.getAttribute('data-permission');
            document.getElementById('menuOrder').value = button.getAttribute('data-order');
            document.getElementById('menuParentId').value = button.getAttribute('data-parent');
            document.getElementById('menuIsActive').value = button.getAttribute('data-active');
            new bootstrap.Modal(document.getElementById('menuModal')).show();
        }

        function deleteMenu(encryptedId) {
            App.confirm({
                title: 'Hapus Menu',
                text: 'Data akan dipindahkan ke sampah.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/menus') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        menuTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            menuTable.search(this.value);
        });

        document.getElementById('menuForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('menuModal')).hide();
                    App.alert('success', response.message);
                    menuTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var menuTable = App.infiniteScroll({
            container: '#menuScroll',
            body: '#menuTableBody',
            endpoint: '{{ route('menus.paginate') }}',
            pageSize: 10,
            rowRenderer: menuRow
        });

        menuTable.load(true);
    </script>
@endpush
