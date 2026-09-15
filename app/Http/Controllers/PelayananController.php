<?php

namespace App\Http\Controllers;

use App\Http\Requests\PelayananRequest;
use App\Services\PelayananService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelayananController extends Controller
{
    protected $pelayananService;

    public function __construct(PelayananService $pelayananService)
    {
        $this->pelayananService = $pelayananService;
    }

    public function index(): View
    {
        return view('pelayanan.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->pelayananService->paginate([
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

    public function store(PelayananRequest $request): JsonResponse
    {
        $this->pelayananService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pelayanan berhasil ditambahkan.',
        ]);
    }

    public function update(PelayananRequest $request, string $pelayanan): JsonResponse
    {
        $this->pelayananService->update(id_decode($pelayanan), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Pelayanan berhasil diperbarui.',
        ]);
    }

    public function destroy(string $pelayanan): JsonResponse
    {
        $this->pelayananService->delete(id_decode($pelayanan));

        return response()->json([
            'success' => true,
            'message' => 'Pelayanan berhasil dihapus.',
        ]);
    }

    public function trashed(): View
    {
        $pelayanan = $this->pelayananService->getTrashed();

        return view('pelayanan.trashed', compact('pelayanan'));
    }

    public function restore(string $pelayanan): JsonResponse
    {
        $this->pelayananService->restore(id_decode($pelayanan));

        return response()->json([
            'success' => true,
            'message' => 'Pelayanan berhasil dikembalikan.',
        ]);
    }

    public function forceDelete(string $pelayanan): JsonResponse
    {
        $this->pelayananService->forceDelete(id_decode($pelayanan));

        return response()->json([
            'success' => true,
            'message' => 'Pelayanan berhasil dihapus permanen.',
        ]);
    }
}
