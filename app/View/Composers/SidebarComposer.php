<?php

namespace App\View\Composers;

use App\Services\MenuService;
use Illuminate\View\View;

class SidebarComposer
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function compose(View $view)
    {
        $user = auth()->user();
        $isSuperAdmin = $user && $user->hasRole('Super Admin');

        $menus = $this->menuService->getActiveMenus()->filter(function ($menu) use ($user, $isSuperAdmin) {
            if ($isSuperAdmin) {
                return true;
            }

            if (empty($menu->permission_name)) {
                return true;
            }

            return $user && $user->can($menu->permission_name);
        })->values();

        $tree = $menus->where('parent_id', null)->map(function ($menu) use ($menus) {
            $menu->children = $menus->where('parent_id', $menu->id)->values();

            return $menu;
        });

        $view->with('sidebarMenus', $tree);
    }
}
