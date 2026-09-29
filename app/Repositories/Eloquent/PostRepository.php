<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;

class PostRepository extends BaseRepository implements PostRepositoryInterface
{
    protected function model(): string
    {
        return Post::class;
    }

    public function getPaginated(array $options = []): array
    {
        $query = $this->newQuery()->with('category');

        if (!empty($options['category_id'])) {
            $query->where('category_id', $options['category_id']);
        }

        return CursorPaginationHelper::paginate($query, $options);
    }
}
