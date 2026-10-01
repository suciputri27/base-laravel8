<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Repositories\Contracts\PublikasiRepositoryInterface;
use App\Services\ActivityLogger;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublikasiService extends BaseService
{
    public function __construct(PublikasiRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        if (isset($data['berkas']) && $data['berkas'] instanceof UploadedFile) {
            $data['berkas'] = FileUploadHelper::upload($data['berkas'], 'publikasi');
        }

        $publikasi = $this->repository->create($data);

        ActivityLogger::log('Admin menambahkan Publikasi "' . $publikasi->judul . '".', $publikasi);

        return $publikasi;
    }

    public function update(int $id, array $data)
    {
        $model = $this->repository->findOrFail($id);

        if (isset($data['berkas']) && $data['berkas'] instanceof UploadedFile) {
            if ($model->berkas) {
                FileUploadHelper::delete($model->berkas);
            }

            $data['berkas'] = FileUploadHelper::upload($data['berkas'], 'publikasi');
        } else {
            unset($data['berkas']);
        }

        $publikasi = $this->repository->update($id, $data);

        ActivityLogger::log('Admin memperbarui Publikasi "' . $publikasi->judul . '".', $publikasi);

        return $publikasi;
    }

    public function delete(int $id): bool
    {
        $publikasi = $this->repository->findOrFail($id);

        if ($publikasi->berkas) {
            FileUploadHelper::delete($model->berkas);
        }

        ActivityLogger::log('Admin menghapus Publikasi "' . $publikasi->judul . '".', $publikasi);

        return $this->repository->delete($id);
    }

    public function forceDelete(int $id): bool
    {
        $model = $this->repository->newQuery()->withTrashed()->findOrFail($id);

        if ($model->berkas) {
            FileUploadHelper::delete($model->berkas);
        }

        return $this->repository->forceDelete($id);
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['judul', 'deskripsi', 'jenis_dokumen'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($publikasi) {
            return [
                'encrypted_id' => id_encode((int) $publikasi->id),
                'judul' => $publikasi->judul,
                'jenis_dokumen' => $publikasi->jenis_dokumen,
                'deskripsi' => $publikasi->deskripsi,
                'berkas' => $publikasi->berkas,
                'berkas_url' => $publikasi->berkas ? storage_url($publikasi->berkas) : null,
                'is_active' => (bool) $publikasi->is_active
            ];
        })->values();

        return $result;
    }

    public function publikasi_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = $this->repository->newQuery()
            ->where('jenis_dokumen', 5);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $publikasi = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $publikasi->items(),
            'current_page' => $publikasi->currentPage(),
            'last_page'    => $publikasi->lastPage(),
            'total'        => $publikasi->total(),
        ]);
    }

    public function getMaklumat()
    {
        return $this->repository->newQuery()
            ->where('jenis_dokumen', 1)
            ->get();
    }

    public function standar_pelayanan_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = $this->repository->newQuery()
            ->where('jenis_dokumen', 2);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $publikasi = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $publikasi->items(),
            'current_page' => $publikasi->currentPage(),
            'last_page'    => $publikasi->lastPage(),
            'total'        => $publikasi->total(),
        ]);
    }

    public function sop_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = $this->repository->newQuery()
            ->where('jenis_dokumen', 3);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $publikasi = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $publikasi->items(),
            'current_page' => $publikasi->currentPage(),
            'last_page'    => $publikasi->lastPage(),
            'total'        => $publikasi->total(),
        ]);
    }

    public function getAlurPengaduan()
    {
        return $this->repository->newQuery()
            ->where('jenis_dokumen', 4)
            ->get();
    }
}
