@extends('layouts.app')

@section('title', 'Profile')
@section('page-title', 'Profile')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Profile</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari profile..." style="width: 240px; margin-right: 8px;">
                <a href="{{ route('profiledinas.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Profile
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="postScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Website</th>
                            <th>Profile</th>
                            <th>Alamat</th>
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
        function profileRow(item) {
            var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';

            return '<tr>' +
                '<td>' + App.escapeHtml(item.nama_website) + '</td>' +
                '<td>' + App.escapeHtml(item.tentang) + '</td>' +
                '<td>' + App.escapeHtml(item.alamat) + '</td>' +
                '<td>' +
                    '<a class="btn btn-secondary btn-sm" href="' + '{{ url('profiledinas') }}/' + item.encrypted_id + '/edit' + '"><i class="fas fa-edit"></i></a> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePost(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function deletePost(encryptedId) {
            if (!confirm('Yakin ingin menghapus profile ini?')) {
                return;
            }

            App.submitData({
                url: '{{ url('profiledinas') }}/' + encryptedId,
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
            endpoint: '{{ route('profiledinas.paginate') }}',
            pageSize: 10,
            rowRenderer: profileRow
        });

        postTable.load(true);
    </script>
@endpush
