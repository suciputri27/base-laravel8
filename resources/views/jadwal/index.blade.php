@extends('layouts.app')

@section('title', 'Jadwal')
@section('page-title', 'Jadwal')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Data Jadwal</h5>
            <div class="d-flex align-items-center">
                <select id="jenisFilter" class="form-control" style="width: 220px; margin-right: 8px;">
                    <option value="">Semua Jenis layanan</option>
                    <option value="1">Layanan Rutin</option>
                    <option value="2">Layanan keliling</option>
                </select>
                <input type="text" id="searchInput" class="form-control" placeholder="Cari jadwal..." style="width: 240px; margin-right: 8px;">
                <button type="button" class="btn btn-primary btn-sm" onclick="openJadwalModal()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-scroll" id="jadwalScroll">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Jenis Layanan</th>
                            <th>Day/Tanggal</th>
                            <th>Lokasi</th>
                            <th>Open</th>
                            <th>Close</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="jadwalTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="jadwalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="jadwalForm" action="{{ route('jadwal.store') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="_method" id="jadwalMethod" value="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="jadwalModalTitle">Tambah Jadwal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">Jenis Layanan</label>
                            <select name="jenis" id="jadwalJenis" class="form-control">
                                <option value=""></option>
                                <option value="1">Layanan Rutin</option>
                                <option value="2">Layanan Keliling</option>
                            </select>
                        </div>
                        <div class="form-group" id="dayGroup">
                            <label class="form-label">Day</label>
                            <select name="day" id="jadwalDay" class="form-control">
                                <option value=""></option>
                                <option value="1">Senin</option>
                                <option value="2">Selasa</option>
                                <option value="3">Rabu</option>
                                <option value="4">Kamis</option>
                                <option value="5">Jumat</option>
                                <option value="6">Sabtu</option>
                                <option value="7">Minggu</option>
                            </select>
                        </div>
                        <div class="form-group" id="tanggalGroup" style="display: none;">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" id="jadwalTanggal" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Open</label>
                            <input type="time" name="open" id="jadwalOpen" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Close</label>
                            <input type="time" name="close" id="jadwalClose" class="form-control" required>
                        </div>
                        <div class="form-group" id="lokasiGroup" style="display: none;">
                            <label class="form-label">Tempat</label>
                            <textarea type="text" name="tempat" id="jadwalTempat" class="form-control"></textarea>
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
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#jadwalJenis').select2({
                width: '100%',
                placeholder: 'Pilih Jenis',
                dropdownParent: $('#jadwalModal')
            });

            $('#jadwalDay').select2({
                width: '100%',
                placeholder: 'Pilih Hari',
                dropdownParent: $('#jadwalModal')
            });
        });

        function toggleFieldsByJenis() {
            if ($('#jadwalJenis').val() === '2') {
                // Keliling: tampilkan tanggal + lokasi, sembunyikan day
                $('#tanggalGroup').show();
                $('#lokasiGroup').show();
                $('#dayGroup').hide();

                $('#jadwalTanggal').attr('required', true);
                $('#jadwalTempat').attr('required', true);
                $('#jadwalDay').val('').trigger('change').removeAttr('required');
            } else {
                // Rutin: tampilkan day, sembunyikan tanggal + lokasi
                $('#tanggalGroup').hide();
                $('#lokasiGroup').hide();
                $('#dayGroup').show();

                $('#jadwalDay').attr('required', true);
                $('#jadwalTanggal').removeAttr('required').val('');
                $('#jadwalTempat').removeAttr('required').val('');
            }
        }

        $('#jadwalJenis').on('change', function () {
            toggleFieldsByJenis();
        });

        var jenisLabel = {
            1: 'Layanan Rutin',
            2: 'Layanan Keliling'
        };

        function jadwalRow(item) {
            var rowNumber = document.querySelectorAll('#jadwalTableBody tr').length + 1;
            var jenisText = jenisLabel[item.jenis] || '-';
            var dayOrTanggal = item.jenis == 2 ? item.tanggal_formatted : item.day_label;
            var lokasiText = item.tempat || 'Kantor Disdukcapil Agam Lubuk Basung,<br>Kantor Pelayanan Bersama Belakang Balok Bukittingi,<br>Kantor Camat Ampek Angkek,<br>Kantor Walinagari Matua Mudiak';

            return '<tr>' +
                '<td>' + rowNumber + '</td>' +
                '<td>' + jenisText + '</td>' +
                '<td>' + dayOrTanggal + '</td>' +
                '<td>' + lokasiText + '</td>' +
                '<td>' + item.open + '</td>' +
                '<td>' + item.close + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-secondary btn-sm" onclick="editJadwal(\'' + item.encrypted_id + '\', this)" ' +
                        'data-jenis="' + item.jenis + '" data-day="' + item.day + '" data-tanggal="' + (item.tanggal || '') + '" ' +
                        'data-tempat="' + (item.tempat) + '" data-open="' + item.open + '" data-close="' + item.close + '">' +
                        '<i class="fas fa-edit"></i></button> ' +
                    '<button type="button" class="btn btn-danger btn-sm" onclick="deleteJadwal(\'' + item.encrypted_id + '\')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>';
        }

        function openJadwalModal() {
            document.getElementById('jadwalMethod').value = 'POST';
            document.getElementById('jadwalForm').action = '{{ route('jadwal.store') }}';
            document.getElementById('jadwalModalTitle').textContent = 'Tambah Jadwal';

            $('#jadwalJenis').val('').trigger('change');
            $('#jadwalDay').val('').trigger('change');
            document.getElementById('jadwalTanggal').value = '';
            document.getElementById('jadwalOpen').value = '';
            document.getElementById('jadwalClose').value = '';
            document.getElementById('jadwalTempat').value = '';

            new bootstrap.Modal(document.getElementById('jadwalModal')).show();
        }

        function editJadwal(encryptedId, button) {
            document.getElementById('jadwalMethod').value = 'PUT';
            document.getElementById('jadwalForm').action = '{{ url('admin/jadwal') }}/' + encryptedId;
            document.getElementById('jadwalModalTitle').textContent = 'Edit Jadwal';

            $('#jadwalJenis').val(button.getAttribute('data-jenis')).trigger('change');
            $('#jadwalDay').val(button.getAttribute('data-day')).trigger('change');
            document.getElementById('jadwalTanggal').value = button.getAttribute('data-tanggal');
            document.getElementById('jadwalOpen').value = button.getAttribute('data-open');
            document.getElementById('jadwalClose').value = button.getAttribute('data-close');
            document.getElementById('jadwalTempat').value = button.getAttribute('data-tempat');

            new bootstrap.Modal(document.getElementById('jadwalModal')).show();
        }

        function deleteJadwal(encryptedId) {
            App.confirm({
                title: 'Hapus Jadwal',
                confirmButtonText: 'Ya, hapus'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/jadwal') }}/' + encryptedId,
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        jadwalTable.reset();
                    }
                });
            });
        }

        document.getElementById('searchInput').addEventListener('input', function () {
            jadwalTable.search(this.value);
        });

        var jadwalFilters = {
            jenis: ''
        };

        document.getElementById('jenisFilter').addEventListener('change', function () {
            jadwalFilters.jenis = this.value;
            jadwalTable.reset();
        });


        document.getElementById('jadwalForm').addEventListener('submit', function (event) {
            event.preventDefault();

            App.submit(this, {
                onSuccess: function (response) {
                    bootstrap.Modal.getInstance(document.getElementById('jadwalModal')).hide();
                    App.alert('success', response.message);
                    jadwalTable.reset();
                },
                onError: function (payload) {
                    App.alert('danger', payload.message || 'Terjadi kesalahan.');
                }
            });
        });

        var jadwalTable = App.infiniteScroll({
            container: '#jadwalScroll',
            body: '#jadwalTableBody',
            endpoint: '{{ route('jadwal.paginate') }}',
            pageSize: 10,
            rowRenderer: jadwalRow,
            extraParams: jadwalFilters
        });

        jadwalTable.load(true);
    </script>
@endpush