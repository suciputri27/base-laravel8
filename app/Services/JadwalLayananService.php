<?php

namespace App\Services;

use App\Repositories\Contracts\JadwallayananRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class JadwalLayananService extends BaseService
{
    public function __construct(JadwallayananRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            if ($data['jenis'] == 2 && !empty($data['tanggal'])) {
                $data['day'] = Carbon::parse($data['tanggal'])->translatedFormat('l'); // "Senin", "Selasa", dst
            }

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
                'tanggal' => $jadwal->tanggal ? Carbon::parse($jadwal->tanggal)->format('Y-m-d') : null,
                'tanggal_formatted' => $jadwal->tanggal ? Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y (l)') : null,
                'tempat' => $jadwal->tempat,
                'open' => Carbon::parse($jadwal->open)->format('H:i'),
                'close' => Carbon::parse($jadwal->close)->format('H:i'),
            ];
        })->values();

        return $result;
    }

    protected array $urutanHari = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];

    public function getJadwalRutin(): Collection
    {
        return $this->repository->newQuery()
            ->where('jenis', 1)
            ->get()
            ->sortBy(function ($item) {
                $posisi = array_search($item->day, $this->urutanHari);
                return $posisi === false ? 99 : $posisi;
            })
            ->values();
    }

    public function getJadwalKeliling(int $perPage = 6): LengthAwarePaginator
    {
        return $this->repository->newQuery()
            ->where('jenis', 2)
            ->orderBy('tanggal', 'asc')
            ->paginate($perPage);
    }
}
