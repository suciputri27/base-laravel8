<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Publikasi;
use App\Services\PublikasiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class F_PublikasiController extends Controller
{
    protected $publikasiservice;

    public function __construct(PublikasiService $publikasiservice)
    {
        $this->publikasiservice = $publikasiservice;
    }

    public function index()
    {
        return view('frontend.publikasi');
    }
    public function publikasi_data(Request $request): JsonResponse
    {
        return $this->publikasiservice->publikasi_data($request);
    }

    public function maklumat()
    {
        $profil = $this->publikasiservice->getMaklumat();
        return view('frontend.maklumat', compact('profil'));
    }

    public function standar_pelayanan()
    {
        return view('frontend.standar-pelayanan');
    }

    public function standar_pelayanan_data(Request $request): JsonResponse
    {
        return $this->publikasiservice->standar_pelayanan_data($request);
    }

    public function sop()
    {
        return view('frontend.sop');
    }

    public function sop_data(Request $request): JsonResponse
    {
        return $this->publikasiservice->sop_data($request);
    }

    public function alur_pengaduan()
    {
        $profil = $this->publikasiservice->getAlurPengaduan();
        return view('frontend.alur-pengaduan', compact('profil'));
    }
}
