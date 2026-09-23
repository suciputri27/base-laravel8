<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Services\CategoryService;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController_old extends Controller
{
    protected $postService;

    protected $categoryService;

    public function __construct(PostService $postService, CategoryService $categoryService)
    {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
    }

    public function index(): View
    {
        return view('posts.index');
    }

    public function paginate(Request $request): JsonResponse
    {
        $result = $this->postService->paginate([
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
        $categories = $this->categoryService->all();

        return view('posts.create', compact('categories'));
    }

    public function store(PostRequest $request): JsonResponse
    {
        $this->postService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan.',
            'redirect' => route('posts.index'),
        ]);
    }

    public function edit(string $post): View
    {
        $post = $this->postService->find(id_decode($post));
        $categories = $this->categoryService->all();

        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, string $post): JsonResponse
    {
        $this->postService->update(id_decode($post), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diperbarui.',
            'redirect' => route('posts.index'),
        ]);
    }

    public function destroy(string $post): JsonResponse
    {
        $this->postService->delete(id_decode($post));

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus.',
        ]);
    }

    public function trashed(): View
    {
        $posts = $this->postService->getTrashed();

        return view('posts.trashed', compact('posts'));
    }

    public function restore(string $post): JsonResponse
    {
        $this->postService->restore(id_decode($post));

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dikembalikan.',
        ]);
    }

    public function forceDelete(string $post): JsonResponse
    {
        $this->postService->forceDelete(id_decode($post));

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus permanen.',
        ]);
    }
}
