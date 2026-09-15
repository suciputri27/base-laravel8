<?php

namespace App\Services;

use App\Repositories\Eloquent\PelayananRepository;

class PelayananService extends BaseService
{
    public function __construct(PelayananRepository $repository)
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
        $options['search_columns'] = ['nama_pelayanan', 'deskripsi'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($pelayanan) {
            return [
                'encrypted_id' => id_encode((int) $pelayanan->id),
                'nama_pelayanan' => $pelayanan->nama_pelayanan,
                'deskripsi' => $pelayanan->deskripsi,
                'is_active' => (bool) $pelayanan->is_active
            ];
        })->values();

        return $result;
    }

    public function all()
    {
        return $this->repository->newQuery()->where('status', true)->orderBy('nama_pelayanan')->get();
    }
}
