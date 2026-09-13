@extends('layouts.app')

@section('title', 'Kategori')
@section('page-title', 'Kategori')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Kategori</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari kategori..." style="width: 240px; margin-right: 8px;">
                @if(auth()->user()->hasRole('Super Admin'))
                    <a href="{{ route('categories.trashed') }}" class="btn btn-outline-secondary btn-sm" style="margin-right: 8px;">
                        <i class="fas fa-trash-restore"></i> Sampah
                    </a>
                @endif
                <button type="button" class="btn btn-primary btn-sm" onclick="openCategoryModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="categoryScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Slug</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="categoryTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="categoryForm" action="{{ route('categories.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="categoryMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="categoryName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description" id="categoryDescription" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="categoryIsActive" class="form-control">
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
        function categoryRow(item) {
            var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';

            return '<tr>' +
                '<td>' + App.escapeHtml(item.name) + '</td>' +
                '<td>' + App.escapeHtml(item.slug) + '</td>' +
                '<td>' + App.escapeHtml(item.description || '-') + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editCategory(\'' + item.encrypted_id + '\', this)" data-name="' + App.escapeHtml(item.name) + '" data-description="' + App.escapeHtml(item.description || '') + '" data-active="' + (item.is_active ? '1' : '0') + '"><i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteCategory(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openCategoryModal() {
            document.getElementById('categoryMethod').value = 'POST';
            document.getElementById('categoryForm').action = '{{ route('categories.store') }}';
            document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori';
            document.getElementById('categoryName').value = '';
            document.getElementById('categoryDescription').value = '';
            document.getElementById('categoryIsActive').value = '1';
            new bootstrap.Modal(document.getElementById('categoryModal')).show();
        }

        function editCategory(encryptedId, button) {
            document.getElementById('categoryMethod').value = 'PUT';
            document.getElementById('categoryForm').action = '{{ url('categories') }}/' + encryptedId;
            document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
            document.getElementById('categoryName').value = button.getAttribute('data-name');
            document.getElementById('categoryDescription').value = button.getAttribute('data-description');
            document.getElementById('categoryIsActive').value = button.getAttribute('data-active');
            new bootstrap.Modal(document.getElementById('categoryModal')).show();
        }

        function deleteCategory(encryptedId) {
            App.confirm({
                title: 'Hapus Kategori',
                text: 'Data akan dipindahkan ke sampah.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('categories') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        categoryTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            categoryTable.search(this.value);
        });

        document.getElementById('categoryForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
                    App.alert('success', response.message);
                    categoryTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var categoryTable = App.infiniteScroll({
            container: '#categoryScroll',
            body: '#categoryTableBody',
            endpoint: '{{ route('categories.paginate') }}',
            pageSize: 10,
            rowRenderer: categoryRow
        });

        categoryTable.load(true);
    </script>
@endpush
