<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\frontend\F_Informasi;
use App\Models\frontend\F_Inovasi;

class F_BerandaController extends Controller
{
    public function index()
    {
        $heroberita = F_Informasi::with('kategori')->orderBy('created_at', 'desc')->limit(3)->get();
        $inovasis = F_Inovasi::orderBy('created_at', 'desc')->limit(3)->get();
        return $inovasis;
        die;
        return view('frontend.beranda', compact('heroberita', 'inovasis'));
    }
}
