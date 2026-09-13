@extends('layouts.app')

@section('title', 'Sampah User')
@section('page-title', 'Sampah User')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">User Terhapus</h5>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->getRoleNames()->first() ?: 'Tanpa Role' }}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="restoreUser('{{ $user->encrypted_id }}')">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="forceDeleteUser('{{ $user->encrypted_id }}')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada user terhapus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function restoreUser(encryptedId) {
            App.submitData({
                url: '{{ url('users') }}/' + encryptedId + '/restore',
                method: 'POST',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }

        function forceDeleteUser(encryptedId) {
            if (!confirm('Yakin ingin menghapus user ini secara permanen?')) {
                return;
            }

            App.submitData({
                url: '{{ url('users') }}/' + encryptedId + '/force-delete',
                method: 'DELETE',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }
    </script>
@endpush
