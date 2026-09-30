<?php

namespace App\Services;

use App\Repositories\Contracts\JadwallayananRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalLayananService extends BaseService
{
    public function __construct(JadwallayananRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $jadwal = $this->repository->create($data);
    
            ActivityLogger::log('Admin menambahkan Jadwal.', $jadwal);
    
            return $jadwal;
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $jadwal = $this->repository->update($id, $data);
    
            ActivityLogger::log('Admin memperbarui Jadwal.', $jadwal);
    
            return $jadwal;
        });
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['day'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($jadwal) {
            return [
                'encrypted_id' => id_encode((int) $jadwal->id),
                'jenis' => $jadwal->jenis,
                'day' => $jadwal->day,
                'open' => Carbon::parse($jadwal->open)->format('H:i'),
                'close' => Carbon::parse($jadwal->close)->format('H:i'),
            ];
        })->values();

        return $result;
    }
}
