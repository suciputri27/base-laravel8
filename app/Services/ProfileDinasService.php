<?php

namespace App\Services;

use App\Repositories\Contracts\ProfileDinasRepositoryInterface;
use Illuminate\Support\Str;

class ProfileDinasService extends BaseService
{
    public function __construct(ProfileDinasRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['nama_website', 'tentang', 'alamat'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($profiledinas) {
            return [
                'encrypted_id' => id_encode((int) $profiledinas->id),
                'nama_website' => $profiledinas->nama_website,
                'tentang' => $profiledinas->tentang,
                'alamat' => $profiledinas->alamat
            ];
        })->values();

        return $result;
    }
}
