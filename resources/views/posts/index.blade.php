@extends('layouts.app')

@section('title', 'Berita')
@section('page-title', 'Berita')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Berita</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari berita..." style="width: 240px; margin-right: 8px;">
                <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Berita
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="postScroll">
                <table class="table">
                    <thead>
                        <tr>
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
            var thumbnail = item.thumbnail_url
                ? '<img src="' + App.escapeHtml(item.thumbnail_url) + '" alt="" class="post-thumb">'
                : '<div class="post-thumb post-thumb-empty"><i class="fas fa-image"></i></div>';

            var category = item.category_name
                ? '<span class="badge badge-secondary">' + App.escapeHtml(item.category_name) + '</span>'
                : '';

            var status = item.status === 'published'
                ? '<span class="badge badge-success">Terbit</span>'
                : '<span class="badge badge-secondary">Draf</span>';

            return '<tr>' +
                '<td>' + thumbnail + '</td>' +
                '<td>' +
                    '<div class="post-title-cell">' +
                        '<div class="post-title">' + App.escapeHtml(item.title) + '</div>' +
                        '<div class="post-meta">' + category + '</div>' +
                    '</div>' +
                '</td>' +
                '<td>' + status + '</td>' +
                '<td>' + App.escapeHtml(item.published_at || '-') + '</td>' +
                '<td>' +
                    '<a class="btn btn-secondary btn-sm" href="' + '{{ url('posts') }}/' + item.encrypted_id + '/edit' + '"><i class="fas fa-edit"></i></a> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePost(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function deletePost(encryptedId) {
            if (!confirm('Yakin ingin menghapus berita ini?')) {
                return;
            }

            App.submitData({
                url: '{{ url('posts') }}/' + encryptedId,
                method: 'DELETE',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    postTable.reset();
                }
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            postTable.search(this.value);
        });

        var postTable = App.infiniteScroll({
            container: '#postScroll',
            body: '#postTableBody',
            endpoint: '{{ route('posts.paginate') }}',
            pageSize: 10,
            rowRenderer: postRow
        });

        postTable.load(true);
    </script>
@endpush
