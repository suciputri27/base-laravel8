@extends('layouts.app')

@section('title', 'Kelola Persyaratan')
@section('page-title', 'Kelola Persyaratan')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h5 class="mb-0">Persyaratan: {{ $pelayanan->nama_pelayanan }}</h5>
            <small class="text-muted" id="ringkasanAktif"></small>
        </div>
        <a href="{{ route('pelayanan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    <div class="card-body">
        <div id="checklistContainer">
            <div class="text-center text-muted py-4">Memuat data...</div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-primary" id="btnSimpanChecklist">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="RiwayatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Riwayat Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-unstyled mb-0" id="riwayatList"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="GantiTemplateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ganti Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="file" id="gantiTemplateFile" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                <small class="text-muted">Versi lama akan otomatis diarsipkan, tidak dihapus.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasiGanti">Upload</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const CHECKLIST_URL = '{{ route('detail_persyaratan.checklist', $pelayanan->encrypted_id) }}';
    const SYNC_URL = '{{ route('detail_persyaratan.sync', $pelayanan->encrypted_id) }}';
    const GANTI_TEMPLATE_URL_BASE = '{{ url('admin/detail-persyaratan') }}';
    const RIWAYAT_URL_BASE = '{{ url('admin/pelayanan/' . $pelayanan->encrypted_id .'/persyaratan') }}';

    let checklistData = [];
    let detailIdSedangDiganti = null;

    function loadChecklist() {
        fetch(CHECKLIST_URL)
            .then(res => res.json())
            .then(json => {
                if (!json.success) {
                    App.alert('danger', 'Gagal memuat data persyaratan.');
                    return;
                }
                checklistData = json.data;
                renderChecklist();
            })
            .catch(() => App.alert('danger', 'Gagal memuat data persyaratan.'));
    }

    function renderChecklist() {
        const container = document.getElementById('checklistContainer');

        if (checklistData.length === 0) {
            container.innerHTML = '<div class="text-center text-muted py-4">Belum ada master persyaratan.</div>';
            return;
        }

        container.innerHTML = '<div class="list-group" id="checklistList"></div>';
        const list = document.getElementById('checklistList');

        checklistData.forEach(function(item) {
            const row = document.createElement('div');
            row.className = 'list-group-item';

            const nonaktifBadge = !item.master_aktif ?
                '<span class="badge badge-warning ms-2">Nonaktif di master</span>' :
                '';

            let aksiArea = '';

            if (item.cekdokumen) {
                if (item.berkas_url) {
                    aksiArea =
                        '<a href="' + App.escapeHtml(item.berkas_url) + '" target="_blank" class="small me-2"><i class="fas fa-file"></i> Lihat berkas</a>' +
                        '<button type="button" class="btn btn-outline-secondary btn-sm me-1" onclick="openGantiTemplate(\'' + item.detail_encrypted_id + '\')"><i class="fas fa-sync"></i> Ganti Template</button>' +
                        '<button type="button" class="btn btn-outline-secondary btn-sm" onclick="openRiwayat(\'' + item.persyaratan_id + '\')"><i class="fas fa-history"></i> Riwayat</button>';
                } else {
                    aksiArea = '<input type="file" class="form-control form-control-sm" data-persyaratan-id="' + item.persyaratan_id + '" style="max-width:260px;">';
                }
            }
            // kalau cekdokumen = false, aksiArea dibiarkan kosong (tidak ada slot upload sama sekali)

            row.innerHTML =
                '<div class="form-check">' +
                '<input class="form-check-input checklist-checkbox" type="checkbox" value="' + item.persyaratan_id + '" id="chk-' + item.persyaratan_id + '" ' + (item.checked ? 'checked' : '') + '>' +
                '<label class="form-check-label fw-medium" for="chk-' + item.persyaratan_id + '">' +
                App.escapeHtml(item.nama_persyaratan) + nonaktifBadge +
                '</label>' +
                '</div>' +
                (aksiArea ? '<div class="mt-2 upload-slot" id="upload-slot-' + item.persyaratan_id + '" style="' + (item.checked ? '' : 'display:none;') + '">' + aksiArea + '</div>' : '');

            list.appendChild(row);

            if (aksiArea) {
                row.querySelector('.checklist-checkbox').addEventListener('change', function() {
                    document.getElementById('upload-slot-' + item.persyaratan_id).style.display = this.checked ? '' : 'none';
                });
            }
        });

        updateRingkasan();
    }

    function updateRingkasan() {
        const total = checklistData.length;
        const aktif = checklistData.filter(i => i.checked).length;
        document.getElementById('ringkasanAktif').textContent = aktif + ' dari ' + total + ' persyaratan aktif';
    }

    document.getElementById('btnSimpanChecklist').addEventListener('click', function() {
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        document.querySelectorAll('.checklist-checkbox:checked').forEach(function(chk) {
            formData.append('persyaratan_ids[]', chk.value);
        });

        document.querySelectorAll('input[type="file"][data-persyaratan-id]').forEach(function(input) {
            if (input.files && input.files[0]) {
                formData.append('berkas[' + input.dataset.persyaratanId + ']', input.files[0]);
            }
        });

        App.submitData({
            url: SYNC_URL,
            method: 'POST',
            data: formData,
            onSuccess: function(response) {
                App.alert('success', response.message);
                loadChecklist();
            },
            onError: function(payload) {
                App.alert('danger', payload.message || 'Terjadi kesalahan.');
            }
        });
    });

    function openGantiTemplate(detailEncryptedId) {
        detailIdSedangDiganti = detailEncryptedId;
        document.getElementById('gantiTemplateFile').value = '';
        new bootstrap.Modal(document.getElementById('GantiTemplateModal')).show();
    }

    document.getElementById('btnKonfirmasiGanti').addEventListener('click', function() {
        const fileInput = document.getElementById('gantiTemplateFile');

        if (!fileInput.files[0]) {
            App.alert('danger', 'Pilih file terlebih dahulu.');
            return;
        }

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('berkas', fileInput.files[0]);

        App.submitData({
            url: GANTI_TEMPLATE_URL_BASE + '/' + detailIdSedangDiganti + '/ganti-template',
            method: 'POST',
            data: formData,
            onSuccess: function(response) {
                bootstrap.Modal.getInstance(document.getElementById('GantiTemplateModal')).hide();
                App.alert('success', response.message);
                loadChecklist();
            },
            onError: function(payload) {
                App.alert('danger', payload.message || 'Gagal mengganti template.');
            }
        });
    });

    function openRiwayat(persyaratanId) {
        fetch(RIWAYAT_URL_BASE + '/' + persyaratanId + '/riwayat')
            .then(res => res.json())
            .then(json => {
                const list = document.getElementById('riwayatList');
                list.innerHTML = '';

                if (!json.success || json.data.length === 0) {
                    list.innerHTML = '<li class="text-muted">Belum ada riwayat.</li>';
                } else {
                    json.data.forEach(function(item) {
                        const status = item.is_active ?
                            '<span class="badge badge-success">Aktif</span>' :
                            '<span class="badge badge-secondary">Arsip</span>';

                        list.innerHTML +=
                            '<li class="mb-2 pb-2 border-bottom">' +
                            (item.berkas_url ? '<a href="' + App.escapeHtml(item.berkas_url) + '" target="_blank">Lihat berkas</a>' : '<span class="text-muted">Tanpa berkas</span>') +
                            ' ' + status +
                            '<div class="small text-muted">Diupload ' + App.escapeHtml(item.created_at || '-') + (item.created_by ? ' oleh ' + App.escapeHtml(item.created_by) : '') + '</div>' +
                            '</li>';
                    });
                }

                new bootstrap.Modal(document.getElementById('RiwayatModal')).show();
            })
            .catch(() => App.alert('danger', 'Gagal memuat riwayat.'));
    }

    loadChecklist();
</script>
@endpush