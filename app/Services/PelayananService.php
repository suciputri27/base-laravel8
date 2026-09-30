<?php

namespace App\Services;

use App\Repositories\Eloquent\PelayananRepository;
use App\Services\ActivityLogger;

class PelayananService extends BaseService
{
    public function __construct(PelayananRepository $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        $pelayanan = $this->repository->create($data);

        ActivityLogger::log('Admin menambahkan Pelayanan "' . $pelayanan->nama_pelayanan . '".', $pelayanan);

        return $pelayanan;
    }

    public function update(int $id, array $data)
    {
        $pelayanan = $this->repository->update($id, $data);

        ActivityLogger::log('Admin menambahkan Pelayanan "' . $pelayanan->nama_pelayanan . '".', $pelayanan);

        return $pelayanan;
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
        return $this->repository->newQuery()->where('is_active', true)->orderBy('nama_pelayanan')->get();
    }
}
