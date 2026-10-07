<?php

namespace App\Services;

use App\Helpers\IndonesianDateHelper;
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
                // Nilai asli untuk edit
                'day' => $jadwal->day,       
                // Nilai untuk ditampilkan
                'day_label' => IndonesianDateHelper::day($jadwal->day),        
                'tanggal' => $jadwal->tanggal ? Carbon::parse($jadwal->tanggal)->format('Y-m-d'): null,       
                'tanggal_formatted' => $jadwal->tanggal ? IndonesianDateHelper::fullDate($jadwal->tanggal): null,
                'tempat' => $jadwal->tempat,      
                'open' => Carbon::parse($jadwal->open)->format('H:i'),
                'close' => Carbon::parse($jadwal->close)->format('H:i'),
            ];
        })->values();

        return $result;
    }

    public function getJadwalRutin(): Collection
    {
        $hariIniAngka = Carbon::now()->dayOfWeekIso;

        return $this->repository->newQuery()
            ->where('jenis', 1)
            ->get()
            ->sortBy(function ($item) {
                return (int) $item->day;
            })
            ->map(function ($item) use ($hariIniAngka) {
                $item->day_label = IndonesianDateHelper::day($item->day);

                $item->is_today = (int) $item->day === $hariIniAngka;

                return $item;
            })
            ->values();
    }

    public function getJadwalKeliling(int $perPage = 6): LengthAwarePaginator
    {
        $result = $this->repository->newQuery()
            ->where('jenis', 2)
            ->orderBy('tanggal', 'desc')
            ->paginate($perPage);

        $result->getCollection()->transform(function ($jadwal) {
            $tanggal = Carbon::parse($jadwal->tanggal);

            $jadwal->tanggal_angka = $tanggal->format('d');

            $jadwal->tanggal_bulan = $tanggal
                ->locale('id')
                ->translatedFormat('M');

            $jadwal->day_label = IndonesianDateHelper::day(
                $tanggal->dayOfWeekIso
            );

            if ($tanggal->isPast() && !$tanggal->isToday()) {
                $jadwal->status = 'lewat';
                $jadwal->status_label = 'Sudah Lewat';
            } elseif ($tanggal->isToday()) {
                $jadwal->status = 'hari_ini';
                $jadwal->status_label = 'Hari Ini';
            } elseif ($tanggal->diffInDays(now()) <= 7) {
                $jadwal->status = 'segera';
                $jadwal->status_label = 'Segera';
            } else {
                $jadwal->status = null;
                $jadwal->status_label = null;
            }

            return $jadwal;
        });

        return $result;
    }
}
