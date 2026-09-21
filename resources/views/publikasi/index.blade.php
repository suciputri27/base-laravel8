@extends('layouts.app')

@section('title', 'Publikasi')
@section('page-title', 'Publikasi')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Publikasi</h5>
            <div class="d-flex align-items-center">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari publikasi..." style="width: 240px; margin-right: 8px;">
                <a href="{{ route('publikasi.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Publikasi
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="publikasiScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Jenis Dokumen</th>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Berkas</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="publikasiTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function publikasiRow(item) {
            var berkas = App.renderThumbnail(item.berkas_url);
            var badgeMap = {
                    1: '<span class="badge badge-success">Maklumat Pelayanan</span>',
                    2: '<span class="badge badge-info">Standar Pelayanan Publik</span>',
                    3: '<span class="badge badge-warning">SOP</span>',
                    4: '<span class="badge badge-purple">Alur Pengaduan</span>',
                    5: '<span class="badge badge-secondary">Publikasi</span>'
                };
            var jenis = badgeMap[item.jenis_dokumen] || '<span class="badge badge-dark">Tidak Diketahui</span>';

            var status = item.is_active
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-danger">Nonaktif</span>';

            return '<tr>' +
                '<td>' + jenis + '</td>' +
                '<td>' + App.escapeHtml(item.judul) + '</td>' +
                '<td>' + App.escapeHtml(item.deskripsi || '-') + '</td>' +
                '<td>' + berkas + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<a class="btn btn-secondary btn-sm" href="' + '{{ url('admin/publikasi') }}/' + item.encrypted_id + '/edit' + '"><i class="fas fa-edit"></i></a> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deletePublikasi(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function deletePublikasi(encryptedId) {
            App.confirm({
                title: 'Hapus Publikasi',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/publikasi') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        publikasiTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            publikasiTable.search(this.value);
        });

        var publikasiTable = App.infiniteScroll({
            container: '#publikasiScroll',
            body: '#publikasiTableBody',
            endpoint: '{{ route('publikasi.paginate') }}',
            pageSize: 10,
            rowRenderer: publikasiRow
        });

        publikasiTable.load(true);
    </script>
@endpush
