<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\MenuService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    protected $menuService;
    protected $dashboardService;

    public function __construct(MenuService $menuService, DashboardService $dashboardService)
    {
        $this->menuService = $menuService;
        $this->dashboardService = $dashboardService;
    }

    public function index(): View
    {
        $menus = $this->menuService->getActiveMenus();
        $stats = $this->dashboardService->getStats();
        $recentPosts = $this->dashboardService->getRecentPosts();
        $recentActivities = $this->dashboardService->getRecentActivities();
        $traffic = $this->dashboardService->getTrafficChart();
        $categoryDistribution = $this->dashboardService->getCategoryDistribution();

        return view('dashboard', compact('menus', 'stats', 'recentPosts',
                    'recentActivities', 'traffic',
                    'categoryDistribution'));
    }
}
