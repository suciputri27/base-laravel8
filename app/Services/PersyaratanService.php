<?php

namespace App\Services;

use App\Repositories\Eloquent\PersyaratanRepository;

class PersyaratanService extends BaseService
{
    public function __construct(PersyaratanRepository $repository)
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

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['nama_persyaratan'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($persyaratan) {
            return [
                'encrypted_id' => id_encode((int) $persyaratan->id),
                'nama_persyaratan' => $persyaratan->nama_persyaratan,
                'cekdokumen' => (bool) $persyaratan->cekdokumen,
                'is_active' => (bool) $persyaratan->is_active
            ];
        })->values();

        return $result;
    }

    public function all()
    {
        return $this->repository->newQuery()->where('is_active', true)->orderBy('nama_persyaratan')->get();
    }
}
