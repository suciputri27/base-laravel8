<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Repositories\Contracts\StrukturOrganisasiRepositoryInterface;
use Illuminate\Http\UploadedFile;

class StrukturOrganisasiService extends BaseService
{
    public function __construct(StrukturOrganisasiRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        if (isset($data['berkas']) && $data['berkas'] instanceof UploadedFile) {
            $data['berkas'] = FileUploadHelper::upload($data['berkas'], 'struktur_organisasi');
        }

        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        $model = $this->repository->findOrFail($id);

        if (isset($data['berkas']) && $data['berkas'] instanceof UploadedFile) {
            if ($model->thumbnail) {
                FileUploadHelper::delete($model->thumbnail);
            }

            $data['berkas'] = FileUploadHelper::upload($data['berkas'], 'struktur_organisasi');
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

        if ($model->thumbnail) {
            FileUploadHelper::delete($model->thumbnail);
        }

        return $this->repository->forceDelete($id);
    }

    public function paginate(array $options = []): array
    {
        // $options['search_columns'] = ['title', 'slug', 'excerpt'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($post) {
            return [
                'encrypted_id' => id_encode((int) $post->id),
                'thumbnail' => $post->berkas,
                'thumbnail_url' => $post->berkas ? storage_url($post->berkas) : null,
                'status' => $post->status
            ];
        })->values();

        return $result;
    }
}
