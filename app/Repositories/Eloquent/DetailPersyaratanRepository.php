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

    public function activeByPelayanan(int $pelayananId)
    {
        return $this->newQuery()
            ->where('pelayanan_id', $pelayananId)
            ->where('is_active', true)
            ->get();
    }

    public function findActive(int $pelayananId, int $persyaratanId)
    {
        return $this->newQuery()
            ->where('pelayanan_id', $pelayananId)
            ->where('persyaratan_id', $persyaratanId)
            ->where('is_active', true)
            ->latest()
            ->first();
    }

    public function historyOf(int $pelayananId, int $persyaratanId)
    {
        return $this->newQuery()
            ->where('pelayanan_id', $pelayananId)
            ->where('persyaratan_id', $persyaratanId)
            ->with('createdBy')
            ->orderByDesc('created_at')
            ->get();
    }
}
