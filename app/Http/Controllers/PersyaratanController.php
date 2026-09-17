<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersyaratanRequest;
use App\Services\PersyaratanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PersyaratanController extends Controller
{
    protected $persyaratanService;

    public function __construct(PersyaratanService $persyaratanService)
    {
        $this->persyaratanService = $persyaratanService;
    }

    public function index(): View
    {
        return view('persyaratan.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->persyaratanService->paginate([
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

    public function store(PersyaratanRequest $request): JsonResponse
    {
        $this->persyaratanService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
        ]);
    }

    public function update(PersyaratanRequest $request, string $persyaratan): JsonResponse
    {
        $this->persyaratanService->update(id_decode($persyaratan), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui.',
        ]);
    }

    public function destroy(string $persyaratan): JsonResponse
    {
        $this->persyaratanService->delete(id_decode($persyaratan));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }

    public function trashed(): View
    {
        $persyaratan = $this->persyaratanService->getTrashed();

        return view('persyaratan.trashed', compact('persyaratan'));
    }

    public function restore(string $persyaratan): JsonResponse
    {
        $this->persyaratanService->restore(id_decode($persyaratan));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dikembalikan.',
        ]);
    }

    public function forceDelete(string $persyaratan): JsonResponse
    {
        $this->persyaratanService->forceDelete(id_decode($persyaratan));

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus permanen.',
        ]);
    }
}
