<?php

namespace App\Http\Controllers;

use App\Http\Requests\MenuRequest;
use App\Services\MenuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(): View
    {
        $menus = $this->menuService->getTree();

        return view('menus.index', compact('menus'));
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->menuService->paginate([
            'per_page' => (int) $request->input('per_page', 10),
            'cursor' => $request->input('cursor'),
            'search' => $request->input('search'),
        ]);

        return response()->json([
            'success' => true,
            'data' => $result['data'],
            'next_cursor' => $result['next_cursor'],
            'has_more_pages' => $result['has_more_pages'],
        ]);
    }

    public function store(MenuRequest $request): JsonResponse
    {
        $menu = $this->menuService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan.',
            'data' => $menu,
        ]);
    }

    public function update(MenuRequest $request, string $menu): JsonResponse
    {
        $id = id_decode($menu);
        $model = $this->menuService->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui.',
            'data' => $model,
        ]);
    }

    public function destroy(string $menu): JsonResponse
    {
        $this->menuService->delete(id_decode($menu));

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus.',
        ]);
    }

    public function trashed(): View
    {
        $menus = $this->menuService->getTrashed();

        return view('menus.trashed', compact('menus'));
    }

    public function restore(string $menu): JsonResponse
    {
        $this->menuService->restore(id_decode($menu));

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dikembalikan.',
        ]);
    }

    public function forceDelete(string $menu): JsonResponse
    {
        $this->menuService->forceDelete(id_decode($menu));

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus permanen.',
        ]);
    }
}
