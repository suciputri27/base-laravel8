<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\JadwalLayananService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class F_JadwalController extends Controller
{
    protected $jadwalService;

    public function __construct(JadwalLayananService $jadwalService)
    {
        $this->jadwalService = $jadwalService;
    }

    public function index(): View
    {
        $jadwalRutin    = $this->jadwalService->getJadwalRutin();
        $jadwalKeliling = $this->jadwalService->getJadwalKeliling();

        return view('frontend.jadwal', compact('jadwalRutin', 'jadwalKeliling'));
    }

    public function keliling(): JsonResponse
    {
        $jadwalKeliling = $this->jadwalService->getJadwalKeliling();

        return response()->json([
            'success'       => true,
            'data'          => $jadwalKeliling->items(),
            'current_page'  => $jadwalKeliling->currentPage(),
            'last_page'     => $jadwalKeliling->lastPage(),
            'has_more'      => $jadwalKeliling->hasMorePages(),
        ]);
    }
}
