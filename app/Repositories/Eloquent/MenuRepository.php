<?php

namespace App\Repositories\Eloquent;

use App\Helpers\CursorPaginationHelper;
use App\Models\Menu;
use App\Repositories\Contracts\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    protected function model(): string
    {
        return Menu::class;
    }

    public function getPaginated(array $options = []): array
    {
        return CursorPaginationHelper::paginate($this->model->with('parent'), $options);
    }

    public function getActiveMenus(): Collection
    {
        return $this->model
            ->where('is_active', true)
            ->orderBy('order_no')
            ->get();
    }

    public function getTree(): Collection
    {
        return $this->model
            ->with(['children' => function ($query) {
                $query->orderBy('order_no');
            }])
            ->whereNull('parent_id')
            ->orderBy('order_no')
            ->get();
    }
}
