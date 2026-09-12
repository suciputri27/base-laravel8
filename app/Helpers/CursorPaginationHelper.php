<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class CursorPaginationHelper
{
    public static function paginate(Builder $query, array $options = []): array
    {
        $perPage = (int) ($options['per_page'] ?? 10);
        $cursorColumn = $options['cursor_column'] ?? 'id';
        $direction = strtolower($options['direction'] ?? 'desc');
        $cursor = $options['cursor'] ?? null;
        $search = $options['search'] ?? null;
        $searchColumns = $options['search_columns'] ?? [];

        if ($search !== null && $search !== '' && ! empty($searchColumns)) {
            $query->where(function (Builder $q) use ($search, $searchColumns) {
                foreach ($searchColumns as $index => $column) {
                    if ($index === 0) {
                        $q->where($column, 'like', '%' . $search . '%');
                    } else {
                        $q->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        $query->orderBy($cursorColumn, $direction);

        if ($cursor !== null && $cursor !== '') {
            $cursorValue = IdEncryptionHelper::decode((string) $cursor);

            if ($cursorValue !== null) {
                if ($direction === 'desc') {
                    $query->where($cursorColumn, '<', $cursorValue);
                } else {
                    $query->where($cursorColumn, '>', $cursorValue);
                }
            }
        }

        $collection = $query->limit($perPage + 1)->get();

        $hasMorePages = $collection->count() > $perPage;
        $items = $hasMorePages ? $collection->slice(0, $perPage)->values() : $collection;

        $nextCursor = null;

        if ($hasMorePages && $items->isNotEmpty()) {
            $last = $items->last();
            $nextCursor = IdEncryptionHelper::encode((int) $last->{$cursorColumn});
        }

        return [
            'data' => $items,
            'next_cursor' => $nextCursor,
            'has_more_pages' => $hasMorePages,
        ];
    }
}
