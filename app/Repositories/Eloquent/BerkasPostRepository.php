<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Berkas_post;
use App\Repositories\Contracts\BerkasPostRepositoryInterface;

class BerkasPostRepository extends BaseRepository implements BerkasPostRepositoryInterface
{
    protected function model(): string
    {
        return Berkas_post::class;
    }

    public function getPaginated(array $options = []): array
    {
        return CursorPaginationHelper::paginate($this->model->with('posts'), $options);
    }

    public function deleteByIds(array $encryptedIds, int $postId): void
    {
        $ids = array_map(fn ($encId) => id_decode($encId), $encryptedIds);
        $this->newQuery()
            ->where('posts_id', $postId)
            ->whereIn('id', $ids)
            ->get()
            ->each(fn ($berkas) => $berkas->delete());
    }

    public function getByPostId(int $postId)
    {
        return $this->newQuery()->where('posts_id', $postId)->get();
    }
}
