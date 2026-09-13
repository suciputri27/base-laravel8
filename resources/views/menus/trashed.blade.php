@extends('layouts.app')

@section('title', 'Sampah Menu')
@section('page-title', 'Sampah Menu')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Menu Terhapus</h5>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Route atau URL</th>
                        <th>Parent</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                        <tr>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->route_or_url ?: '-' }}</td>
                            <td>{{ $menu->parent ? $menu->parent->name : '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="restoreMenu('{{ $menu->encrypted_id }}')">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="forceDeleteMenu('{{ $menu->encrypted_id }}')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada menu terhapus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function restoreMenu(encryptedId) {
            App.submitData({
                url: '{{ url('menus') }}/' + encryptedId + '/restore',
                method: 'POST',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }

        function forceDeleteMenu(encryptedId) {
            App.confirm({
                title: 'Hapus Permanen',
                text: 'Data yang dihapus permanen tidak bisa dikembalikan.',
                confirmButtonText: 'Ya, hapus permanen'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('menus') }}/' + encryptedId + '/force-delete',
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
