<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Publikasi;
use App\Repositories\Contracts\PublikasiRepositoryInterface;

class PublikasiRepository extends BaseRepository implements PublikasiRepositoryInterface
{
    protected function model(): string
    {
        return Publikasi::class;
    }

    public function getPaginated(array $options = []): array
    {
        $query = $this->newQuery();

        if (!empty($options['jenis_dokumen'])) {
            $query->where('jenis_dokumen', $options['jenis_dokumen']);
        }

        return CursorPaginationHelper::paginate($query, $options);
    }
}
