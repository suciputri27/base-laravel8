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
            if ($model->berkas) {
                FileUploadHelper::delete($model->berkas);
            }

            $data['berkas'] = FileUploadHelper::upload($data['berkas'], 'struktur_organisasi');
        } else {
            unset($data['berkas']);
        }

        return $this->repository->update($id, $data);
    }
    public function paginate(array $options = []): array
    {
        // $options['search_columns'] = ['title', 'slug', 'excerpt'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($struktur) {
            return [
                'encrypted_id' => id_encode((int) $struktur->id),
                'berkas' => $struktur->berkas,
                'berkas_url' => $struktur->berkas ? storage_url($struktur->berkas) : null,
                'status' => $struktur->status
            ];
        })->values();

        return $result;
    }

    public function all()
    {
        return $this->repository->newQuery()->where('status', 1)->orderBy('created_at', 'desc')->get();
    }
}
