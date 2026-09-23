@extends('layouts.app')

@section('title', 'Inovasi')
@section('page-title', 'Inovasi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Inovasi</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari inovasi..." style="width: 240px; margin-right: 8px;">
                <a href="{{ route('inovasi.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Inovasi
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="inovasiScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis</th>
                            <th>Judul</th>
                            <th>Berkas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="inovasiTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function inovasiRow(item) {
            var rowNumber = document.querySelectorAll('#inovasiTableBody tr').length + 1;
            var thumbnailUrl = (item.berkas && item.berkas.length > 0) ? item.berkas[0].url : null;
            var berkas = App.renderThumbnail(thumbnailUrl);
            var badgeMap = {
                    1: '<span class="badge badge-success">Inovasi</span>',
                    2: '<span class="badge badge-info">Layanan Jemput Bola</span>'
                };
            var jenis = badgeMap[item.jenis] || '<span class="badge badge-dark">Tidak Diketahui</span>';

            var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';
            return '<tr>' +
                '<td>' + rowNumber + '</td>' +
                '<td>' + jenis + '</td>' +
                '<td>' + App.escapeHtml(item.judul) + '</td>' +
                '<td>' + berkas + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<a class="btn btn-secondary btn-sm" href="' + '{{ url('admin/inovasi') }}/' + item.encrypted_id + '/edit' + '"><i class="fas fa-edit"></i></a> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteInovasi(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function deleteInovasi(encryptedId) {
            App.confirm({
                title: 'Hapus Inovasi',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/inovasi') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        inovasiTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            inovasiTable.search(this.value);
        });

        var inovasiTable = App.infiniteScroll({
            container: '#inovasiScroll',
            body: '#inovasiTableBody',
            endpoint: '{{ route('inovasi.paginate') }}',
            pageSize: 10,
            rowRenderer: inovasiRow
        });

        inovasiTable.load(true);
    </script>
@endpush
