<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Detail_persyaratan;
use App\Repositories\Contracts\DetailPersyaratanRepositoryInterface;

class DetailPersyaratanRepository extends BaseRepository implements DetailPersyaratanRepositoryInterface
{
    protected function model(): string
    {
        return Detail_persyaratan::class;
    }

    public function getPaginated(array $options = []): array
    {
        return CursorPaginationHelper::paginate(
            $this->model->with(['pelayanan', 'persyaratan']),
            $options
        );
    }
}
