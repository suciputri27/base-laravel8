<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Berkas_inovasi;
use App\Repositories\Contracts\BerkasInovasiRepositoryInterface;

class BerkasInovasiRepository extends BaseRepository implements BerkasInovasiRepositoryInterface
{
    protected function model(): string
    {
        return Berkas_inovasi::class;
    }

    public function getPaginated(array $options = []): array
    {
        return CursorPaginationHelper::paginate($this->model->with('inovasi'), $options);
    }

    public function deleteByIds(array $encryptedIds, int $inovasiId): void
    {
        $ids = array_map(fn ($encId) => id_decode($encId), $encryptedIds);
        $this->newQuery()
            ->where('inovasi_id', $inovasiId)
            ->whereIn('id', $ids)
            ->get()
            ->each(fn ($berkas) => $berkas->delete());
    }

    public function getByInovasiId(int $inovasiId)
    {
        return $this->newQuery()->where('inovasi_id', $inovasiId)->get();
    }
}
