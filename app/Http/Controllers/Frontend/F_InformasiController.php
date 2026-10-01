<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\PostService;
use Illuminate\View\View;

class F_InformasiController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function index(): View
    {
        $posts = $this->postService->getPublishedRecent(10); // atau pakai pagination kalau perlu

        return view('frontend.informasi', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = $this->postService->findPublishedBySlug($slug);

        return view('frontend.detail-info', compact('post'));
    }
}
