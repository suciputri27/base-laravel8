<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Services\RbacService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    protected $rbacService;

    public function __construct(RbacService $rbacService)
    {
        $this->rbacService = $rbacService;
    }

    public function index(): View
    {
        $permissions = $this->rbacService->getPermissions();

        return view('roles.index', compact('permissions'));
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->rbacService->paginateRoles([
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

    public function store(RoleRequest $request): JsonResponse
    {
        $this->rbacService->createRole($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil ditambahkan.',
        ]);
    }

    public function update(RoleRequest $request, string $role): JsonResponse
    {
        $this->rbacService->updateRole(id_decode($role), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil diperbarui.',
        ]);
    }

    public function destroy(string $role): JsonResponse
    {
        $this->rbacService->deleteRole(id_decode($role));

        return response()->json([
            'success' => true,
            'message' => 'Role berhasil dihapus.',
        ]);
    }
}
