<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileDinasRequest;
use App\Services\ProfileDinasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileDinasController extends Controller
{
    protected $profileDinasService;

    public function __construct(ProfileDinasService $profileDinasService)
    {
        $this->profileDinasService = $profileDinasService;
    }

    public function index(): View
    {
        return view('profiledinas.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->profileDinasService->paginate([
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
        return view('profiledinas.create');
    }

    public function store(ProfileDinasRequest $request): JsonResponse
    {
        $this->profileDinasService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile Dinas berhasil ditambahkan.',
        ]);
    }

    public function edit(string $post): View
    {
        $profiledinas = $this->profileDinasService->find(id_decode($post));

        return view('profiledinas.edit', compact('profiledinas'));
    }

    public function update(ProfileDinasRequest $request, string $profiledinas): JsonResponse
    {
        $this->profileDinasService->update(id_decode($profiledinas), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profile Dinas berhasil diperbarui.',
        ]);
    }

    public function destroy(string $profiledinas): JsonResponse
    {
        $this->profileDinasService->delete(id_decode($profiledinas));

        return response()->json([
            'success' => true,
            'message' => 'Profile Dinas berhasil dihapus.',
        ]);
    }
}
