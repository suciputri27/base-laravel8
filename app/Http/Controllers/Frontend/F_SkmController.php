<?php

namespace App\Http\Controllers\Frontend;


use App\Http\Controllers\Controller;
use App\Services\SkmService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class F_SkmController extends Controller
{
    protected $skmService;

    public function __construct(SkmService $skmService)
    {
        $this->skmService = $skmService;
    }

    /**
     * Halaman form SKM.
     */
    public function index(): View
    {
        $pertanyaans = $this->skmService->getSurvei();
        $kode_instansi = 'OPD-9';
        return view('frontend.skm', compact('pertanyaans', 'kode_instansi'));
    }

    /**
     * Submit jawaban survey lewat AJAX (tanpa reload).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username'       => 'required|string|max:100',
            'usia'           => 'required|numeric|min:1|max:120',
            'jenis_kelamin'  => 'required|in:L,P',
            'pendidikan'     => 'required|string',
            'pekerjaan'      => 'required|string',
            'kode_instansi'  => 'required|string',
            'ruangan'        => 'nullable|string',
            'jawaban'        => 'required|array|min:1',
            'jawaban.*'      => 'required|numeric|min:1|max:4',
        ]);

        $berhasil = $this->skmService->insertPenilaian([
            'username'      => $validated['username'],
            'usia'          => $validated['usia'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'pendidikan'    => $validated['pendidikan'],
            'pekerjaan'     => $validated['pekerjaan'],
            'kode_instansi' => $validated['kode_instansi'],
            'ruangan'       => $validated['ruangan'] ?? '',
            'nilai'         => $validated['jawaban'],
        ]);

        if (! $berhasil) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim survey. Silakan coba lagi.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Terima kasih! Survey Anda berhasil dikirim.',
        ]);
    }

    /**
     * Halaman hasil/rekap SKM (data dari API, bukan form).
     */
    public function hasil(): View
    {
        $grafik       = $this->skmService->getGrafikPenilaian();
        $detailUnsur  = $this->skmService->getCountDetailUnsur();
        $surveyor     = $this->skmService->getCountSurveyor();
        $dataIkm      = $this->skmService->getDataIKM();

        // dd($grafik, $detailUnsur, $surveyor, $dataIkm);

        return view('frontend.hasil-skm', compact('grafik', 'detailUnsur', 'surveyor', 'dataIkm'));
    }
}
