@extends('layouts.app')

@section('title', 'Sampah Berita')
@section('page-title', 'Sampah Berita')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Berita Terhapus</h5>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->category ? $post->category->name : '-' }}</td>
                            <td>{{ $post->published_at ? indo_datetime($post->published_at) : '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-success btn-sm" onclick="restorePost('{{ $post->encrypted_id }}')">
                                    <i class="fas fa-undo"></i> Pulihkan
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="forceDeletePost('{{ $post->encrypted_id }}')">
                                    <i class="fas fa-trash"></i> Hapus Permanen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada berita terhapus.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function restorePost(encryptedId) {
            App.submitData({
                url: '{{ url('admin/posts') }}/' + encryptedId + '/restore',
                method: 'POST',
                onSuccess: function (response) {
                    App.alert('success', response.message);
                    window.location.reload();
                }
            });
        }

        function forceDeletePost(encryptedId) {
            App.confirm({
                title: 'Hapus Permanen',
                text: 'Data yang dihapus permanen tidak bisa dikembalikan.',
                confirmButtonText: 'Ya, hapus permanen'
            }).then(function (result) {
                if (!result.isConfirmed) {
                    return;
                }

                App.submitData({
                    url: '{{ url('admin/posts') }}/' + encryptedId + '/force-delete',
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
