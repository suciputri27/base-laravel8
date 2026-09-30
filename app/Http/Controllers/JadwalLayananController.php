<?php

namespace App\Http\Controllers;

use App\Http\Requests\JadwalLayananRequest;
use App\Services\JadwalLayananService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JadwalLayananController extends Controller
{
    protected $jadwalService;

    public function __construct(JadwalLayananService $jadwalService)
    {
        $this->jadwalService = $jadwalService;
    }

    public function index(): View
    {
        return view('jadwal.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->jadwalService->paginate([
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

    public function store(JadwalLayananRequest $request): JsonResponse
    {
        $this->jadwalService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan.',
        ]);
    }

    public function update(JadwalLayananRequest $request, string $jadwal): JsonResponse
    {
        $this->jadwalService->update(id_decode($jadwal), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui.',
        ]);
    }

    public function destroy(string $jadwal): JsonResponse
    {
        $this->jadwalService->delete(id_decode($jadwal));

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus.',
        ]);
    }
}
