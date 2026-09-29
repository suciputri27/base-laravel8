@extends('layouts.app')

@section('title', 'Informasi')
@section('page-title', 'Informasi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Informasi</h5>
            <div class="d-flex align-items-center">
                <select id="categoryFilter" class="form-control" style="width: 200px; margin-right: 8px;">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->encrypted_id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari informasi..." style="width: 240px; margin-right: 8px;">
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Informasi
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="postScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Thumbnail</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="postTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function postRow(item) {
            var rowNumber = document.querySelectorAll('#postTableBody tr').length + 1;
            var thumbnailUrl = (item.berkas && item.berkas.length > 0) ? item.berkas[0].url : null;
            var berkas = App.renderThumbnail(thumbnailUrl);

            var category = item.category_name
                ? '<span class="badge badge-secondary">' + App.escapeHtml(item.category_name) + '</span>'
                : '';

            var status = item.status === 'published'
                ? '<span class="badge badge-success">Terbit</span>'
                : '<span class="badge badge-secondary">Draf</span>';

            return '<tr>' +
                '<td>' + rowNumber + '</td>' +
                '<td>' + berkas + '</td>' +
                '<td>' +
                    '<div class="post-title-cell">' +
                        '<div class="post-title">' + App.escapeHtml(item.title) + '</div>' +
                        '<div class="post-meta">' + category + '</div>' +
                    '</div>' +
                '</td>' +
                '<td>' + status + '</td>' +
                '<td>' + App.escapeHtml(item.published_at || '-') + '</td>' +
                '<td>' +
                    '<a class="btn btn-secondary btn-sm" href="' + '{{ url('admin/posts') }}/' + item.encrypted_id + '/edit' + '"><i class="fas fa-edit"></i></a> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePost(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function deletePost(encryptedId) {
            App.confirm({
                title: 'Hapus Berita',
                text: 'Data akan dipindahkan ke sampah.',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/posts') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        postTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            postTable.search(this.value);
        });

        // objek ini di-pass by reference ke infiniteScroll,
        // jadi perubahan property-nya otomatis kebaca saat load() jalan lagi
        var postFilters = {
            category_id: ''
        };

        document.getElementById('categoryFilter').addEventListener('change', function () {
            postFilters.category_id = this.value;
            postTable.reset();
        });

        var postTable = App.infiniteScroll({
            container: '#postScroll',
            body: '#postTableBody',
            endpoint: '{{ route('posts.paginate') }}',
            pageSize: 10,
            rowRenderer: postRow,
            extraParams: postFilters
        });

        postTable.load(true);
    </script>
@endpush
