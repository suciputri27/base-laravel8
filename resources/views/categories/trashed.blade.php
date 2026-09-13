@extends('layouts.app')

@section('title', 'Sampah Kategori')
@section('page-title', 'Sampah Kategori')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Kategori Terhapus</h5>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="restoreCategory('{{ $category->encrypted_id }}')">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="forceDeleteCategory('{{ $category->encrypted_id }}')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center">Belum ada kategori terhapus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function restoreCategory(encryptedId) {
            App.submitData({
                url: '{{ url('categories') }}/' + encryptedId + '/restore',
                method: 'POST',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }

        function forceDeleteCategory(encryptedId) {
            if (!confirm('Yakin ingin menghapus kategori ini secara permanen?')) {
                return;
            }

            App.submitData({
                url: '{{ url('categories') }}/' + encryptedId + '/force-delete',
                method: 'DELETE',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }
    </script>
@endpush
