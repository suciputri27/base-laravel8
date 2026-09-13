<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        return view('categories.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->categoryService->paginate([
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

    public function store(CategoryRequest $request): JsonResponse
    {
        $this->categoryService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
        ]);
    }

    public function update(CategoryRequest $request, string $category): JsonResponse
    {
        $this->categoryService->update(id_decode($category), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
        ]);
    }

    public function destroy(string $category): JsonResponse
    {
        $this->categoryService->delete(id_decode($category));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }

    public function trashed(): View
    {
        $categories = $this->categoryService->getTrashed();

        return view('categories.trashed', compact('categories'));
    }

    public function restore(string $category): JsonResponse
    {
        $this->categoryService->restore(id_decode($category));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dikembalikan.',
        ]);
    }

    public function forceDelete(string $category): JsonResponse
    {
        $this->categoryService->forceDelete(id_decode($category));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus permanen.',
        ]);
    }
}
