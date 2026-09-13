<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\RbacService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    protected $userService;

    protected $rbacService;

    public function __construct(UserService $userService, RbacService $rbacService)
    {
        $this->userService = $userService;
        $this->rbacService = $rbacService;
    }

    public function index(): View
    {
        $roles = $this->rbacService->getRoles();

        return view('users.index', compact('roles'));
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->userService->paginate([
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

    public function store(UserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan.',
            'data' => $user,
        ]);
    }

    public function update(UserRequest $request, string $user): JsonResponse
    {
        $id = id_decode($user);
        $model = $this->userService->update($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'data' => $model,
        ]);
    }

    public function destroy(string $user): JsonResponse
    {
        $this->userService->delete(id_decode($user));

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.',
        ]);
    }

    public function trashed(): View
    {
        $users = $this->userService->getTrashed();

        return view('users.trashed', compact('users'));
    }

    public function restore(string $user): JsonResponse
    {
        $this->userService->restore(id_decode($user));

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dikembalikan.',
        ]);
    }

    public function forceDelete(string $user): JsonResponse
    {
        $this->userService->forceDelete(id_decode($user));

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus permanen.',
        ]);
    }
}
