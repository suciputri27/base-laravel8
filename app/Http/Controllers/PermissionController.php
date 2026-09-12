<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Services\RbacService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    protected $rbacService;

    public function __construct(RbacService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function index(): View
    {
        return view('permissions.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->rbacService->paginatePermissions([
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

    public function store(PermissionRequest $request): JsonResponse
    {
        $this->rbacService->createPermission($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil ditambahkan.',
        ]);
    }

    public function update(PermissionRequest $request, string $permission): JsonResponse
    {
        $this->rbacService->updatePermission(id_decode($permission), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil diperbarui.',
        ]);
    }

    public function destroy(string $permission): JsonResponse
    {
        $this->rbacService->deletePermission(id_decode($permission));

        return response()->json([
            'success' => true,
            'message' => 'Permission berhasil dihapus.',
        ]);
    }
}
