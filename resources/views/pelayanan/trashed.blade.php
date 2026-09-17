@extends('layouts.app')

@section('title', 'Sampah Pelayanan')
@section('page-title', 'Sampah Pelayanan')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Pelayanan Terhapus</h5>
            <a href="{{ route('pelayanan.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Pelayanan</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelayanan as $pelayanan)
                        <tr>
                            <td>{{ $pelayanan->nama_pelayanan }}</td>
                            <td>{{ $pelayanan->deskripsi }}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="restorePelayanan('{{ $pelayanan->encrypted_id }}')">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="forceDeletePelayanan('{{ $pelayanan->encrypted_id }}')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada Pelayanan terhapus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function restorePelayanan(encryptedId) {
            App.submitData({
                url: '{{ url('admin/pelayanan') }}/' + encryptedId + '/restore',
                method: 'POST',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }

        function forceDeletePelayanan(encryptedId) {
            App.confirm({
                title: 'Hapus Permanen',
                text: 'Data yang dihapus permanen tidak bisa dikembalikan.',
                confirmButtonText: 'Ya, hapus permanen'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/pelayanan') }}/' + encryptedId + '/force-delete',
                    method: 'DELETE',
                    onSuccess: function (response) {
                        App.alert('success', response.message);
                        window.location.reload();
                    }
                });
            });
        }
    </script>
@endpush
