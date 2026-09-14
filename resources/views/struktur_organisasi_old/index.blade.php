@extends('layouts.app')

@section('title', 'Struktur Organisasi')
@section('page-title', 'Strutur Organisasi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Berita</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari Struktur..." style="width: 240px; margin-right: 8px;">
                <a href="{{ route('struktur_organisasi.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Berita
                </a>
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
@endsection

@push('scripts')
    <script>
        function strukturRow(item) {
            var thumbnail = item.thumbnail_url
                ? '<img src="' + App.escapeHtml(item.thumbnail_url) + '" alt="" class="post-thumb">'
                : '<div class="post-thumb post-thumb-empty"><i class="fas fa-image"></i></div>';

            var status = item.status === 1
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-secondary">Tidak Aktif</span>';

            return '<tr>' +
                '<td>' + thumbnail + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<a class="btn btn-secondary btn-sm" href="' + '{{ url('strukturorganisasi') }}/' + item.encrypted_id + '/edit' + '"><i class="fas fa-edit"></i></a> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePost(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function deletePost(encryptedId) {
            App.confirm({
                title: 'Hapus Struktur Organisasi',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('strukturorganisasi') }}/' + encryptedId,
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

        var postTable = App.infiniteScroll({
            container: '#strukturScroll',
            body: '#strukturTableBody',
            endpoint: '{{ route('struktur_organisasi.paginate') }}',
            pageSize: 10,
            rowRenderer: strukturRow
        });

        postTable.load(true);
    </script>
@endpush
