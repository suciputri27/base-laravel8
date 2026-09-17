<?php

namespace App\Http\Controllers;

use App\Http\Requests\PublikasiRequest;
use App\Services\PublikasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublikasiController extends Controller
{
    protected $publikasiService;

    public function __construct(PublikasiService $publikasiService)
    {
        $this->publikasiService = $publikasiService;
    }

    public function index(): View
    {
        return view('publikasi.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->publikasiService->paginate([
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

    public function store(PublikasiRequest $request): JsonResponse
    {
        $this->publikasiService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil ditambahkan.',
        ]);
    }

    public function update(PublikasiRequest $request, string $publikasi): JsonResponse
    {
        $this->publikasiService->update(id_decode($publikasi), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil diperbarui.',
        ]);
    }

    public function destroy(string $publikasi): JsonResponse
    {
        $this->publikasiService->delete(id_decode($publikasi));

        return response()->json([
            'success' => true,
            'message' => 'Publikasi berhasil dihapus.',
        ]);
    }
}
