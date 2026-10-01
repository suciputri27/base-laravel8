<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\InovasiService;
use App\Services\PostService;
use Illuminate\View\View;

class F_BerandaController extends Controller
{
    protected $postService;
    protected $inovasiService;

    public function __construct(
        PostService $postService,
        InovasiService $inovasiService
    ) {
        $this->postService = $postService;
        $this->inovasiService = $inovasiService;
    }

    public function index(): View
    {
        $beritaTerbaru = $this->postService->getPublishedRecent(3);
        $inovasiAktif = $this->inovasiService->getPublishedRecent(3);

        return view('frontend.beranda', compact(
            'beritaTerbaru',
            'inovasiAktif'
        ));
    }
}
