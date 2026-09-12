<?php

namespace App\Services;

use App\Repositories\Contracts\MenuRepositoryInterface;

class MenuService extends BaseService
{
    public function __construct(MenuRepositoryInterface $repository)
    {
        parent::__construct($repository);
    }

    public function getActiveMenus()
    {
        return $this->repository->getActiveMenus();
    }

    public function getTree()
    {
        return $this->repository->getTree();
    }

    public function paginate(array $options = []): array
    {
        $options['search_columns'] = ['name', 'route_or_url', 'permission_name'];

        $result = parent::paginate($options);

        $result['data'] = $result['data']->map(function ($menu) {
            return [
                'encrypted_id' => id_encode((int) $menu->id),
                'name' => $menu->name,
                'icon' => $menu->icon,
                'route_or_url' => $menu->route_or_url,
                'permission_name' => $menu->permission_name,
                'order_no' => $menu->order_no,
                'parent_id' => $menu->parent_id ? id_encode((int) $menu->parent_id) : null,
                'parent_name' => $menu->parent ? $menu->parent->name : null,
                'is_active' => (bool) $menu->is_active,
            ];
        })->values();

        return $result;
    }
}
