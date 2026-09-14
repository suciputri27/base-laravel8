<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Dokumen;
use App\Repositories\Contracts\DokumenRepositoryInterface;

class DokumenRepository extends BaseRepository implements DokumenRepositoryInterface
{
    protected function model(): string
    {
        return Dokumen::class;
    }

    public function getPaginated(array $options = []): array
    {
        return CursorPaginationHelper::paginate($this->model->with('jenis_dokumen'), $options);
    }
}
