<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Inovasi;
use App\Repositories\Contracts\InovasiRepositoryInterface;

class InovasiRepository extends BaseRepository implements InovasiRepositoryInterface
{
    protected function model(): string
    {
        return Inovasi::class;
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
