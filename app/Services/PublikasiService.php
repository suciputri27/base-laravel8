<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Repositories\Contracts\PublikasiRepositoryInterface;
use Illuminate\Http\UploadedFile;

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
    
        return $this->repository->create($data);
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

        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
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
}
