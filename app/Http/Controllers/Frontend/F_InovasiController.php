<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\InovasiService;
use Illuminate\View\View;

class F_InovasiController extends Controller
{
    protected $inovasiservice;

    public function __construct(InovasiService $inovasiservice)
    {
        $this->inovasiservice = $inovasiservice;
    }

    public function index()
    {
        $inovasis = $this->inovasiservice->all();
        return view('frontend.inovasi', compact('inovasis'));
    }

    public function show(string $slug): View
    {
        $inovasi = $this->inovasiservice->getBySlug($slug);
        return view('frontend.detail-inovasi', compact('inovasi'));
    }
}
