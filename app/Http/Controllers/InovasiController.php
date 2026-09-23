<?php

namespace App\Http\Controllers;

use App\Http\Requests\InovasiRequest;
use App\Services\InovasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InovasiController extends Controller
{
    protected $inovasiService;

    public function __construct(InovasiService $inovasiService)
    {
        $this->inovasiService = $inovasiService;
    }

    public function index(): View
    {
        return view('inovasi.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->inovasiService->paginate([
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

    public function create(): View
    {
        return view('inovasi.create');
    }

    public function store(InovasiRequest $request): JsonResponse
    {
        $this->inovasiService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Inovasi berhasil ditambahkan.',
            'redirect' => route('inovasi.index'),
        ]);
    }

    public function edit(string $inovasi): View
    {
        $inovasi = $this->inovasiService->find(id_decode($inovasi), ['berkas']);
    
        return view('inovasi.edit', compact('inovasi'));
    }

    public function update(InovasiRequest $request, string $inovasi): JsonResponse
    {
        $this->inovasiService->update(id_decode($inovasi), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Inovasi berhasil diperbarui.',
            'redirect' => route('inovasi.index'),
        ]);
    }

    public function destroy(string $inovasi): JsonResponse
    {
        $this->inovasiService->delete(id_decode($inovasi));

        return response()->json([
            'success' => true,
            'message' => 'Inovasi berhasil dihapus.',
        ]);
    }
}
