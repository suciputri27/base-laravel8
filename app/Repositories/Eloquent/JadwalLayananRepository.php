<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Jadwal_layanan;
use App\Repositories\Contracts\JadwallayananRepositoryInterface;

class JadwalLayananRepository extends BaseRepository implements JadwallayananRepositoryInterface
{
    protected function model(): string
    {
        return Jadwal_layanan::class;
    }

    public function getPaginated(array $options = []): array
    {
        $query = $this->newQuery();

        if (!empty($options['jenis'])) {
            $query->where('jenis', $options['jenis']);
        }

        return CursorPaginationHelper::paginate($query, $options);
    }
}
