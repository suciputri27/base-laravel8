<?php

namespace App\Http\Controllers;

use App\Services\MenuService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(): View
    {
        $menus = $this->menuService->getActiveMenus();

        return view('dashboard', compact('menus'));
    }
}
