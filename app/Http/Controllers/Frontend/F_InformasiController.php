<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\frontend\F_Informasi;
use Illuminate\View\View;

class F_InformasiController extends Controller
{
    public function index()
    {
        $infos = F_Informasi::with('kategori')->orderBy('created_at', 'desc')->get();
        return view('frontend.informasi', compact('infos'));
    }

    public function show(string $slug): View
    {
        $infos = F_Informasi::with('kategori')->where('slug', $slug)->firstOrFail();

        return view('frontend.detail-info', compact('infos'));
    }
}
