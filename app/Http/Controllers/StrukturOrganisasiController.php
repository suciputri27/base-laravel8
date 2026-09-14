<?php

namespace App\Http\Controllers;

use App\Http\Requests\StrukturOrganisasiRequest;
use App\Services\StrukturOrganisasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StrukturOrganisasiController extends Controller
{
    protected $strukturorganisasiService;

    public function __construct(StrukturOrganisasiService $strukturorganisasiService)
    {
        $this->strukturorganisasiService = $strukturorganisasiService;
    }

    public function index(): View
    {
        return view('struktur_organisasi.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->strukturorganisasiService->paginate([
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

    public function store(StrukturOrganisasiRequest $request): JsonResponse
    {
        $this->strukturorganisasiService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan.',
        ]);
    }

    public function update(StrukturOrganisasiRequest $request, string $struktur): JsonResponse
    {
        $this->strukturorganisasiService->update(id_decode($struktur), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Struktur berhasil diperbarui.',
        ]);
    }

    public function destroy(string $struktur): JsonResponse
    {
        $this->strukturorganisasiService->delete(id_decode($struktur));

        return response()->json([
            'success' => true,
            'message' => 'Struktur berhasil dihapus.',
        ]);
    }
}
