<?php

namespace App\Http\Controllers;

use App\Http\Requests\GantiTemplateRequest;
use App\Http\Requests\SyncDetailPersyaratanRequest;
use App\Services\DetailPersyaratanService;
use App\Services\PelayananService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DetailPersyaratanController extends Controller
{
    protected $detailPersyaratanService;

    protected $pelayananService;

    public function __construct(DetailPersyaratanService $detailPersyaratanService, PelayananService $pelayananService)
    {
        $this->detailPersyaratanService = $detailPersyaratanService;
        $this->pelayananService = $pelayananService;
    }

    public function index(string $pelayanan): View
    {
        $pelayanan = $this->pelayananService->find(id_decode($pelayanan));

        return view('detail_persyaratan.index', compact('pelayanan'));
    }

    public function checklist(string $pelayanan): JsonResponse
    {
        $data = $this->detailPersyaratanService->checklist(id_decode($pelayanan));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function sync(SyncDetailPersyaratanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $rawFiles = $request->file('berkas', []);
        $decodedFiles = [];
        foreach ($rawFiles as $encryptedId => $file) {
            $decodedId = id_decode((string) $encryptedId);
            if ($decodedId) {
                $decodedFiles[$decodedId] = $file;
            }
        }
    
        $this->detailPersyaratanService->sync(
            $validated['pelayanan_id'],
            $validated['persyaratan_ids'],
            $decodedFiles
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Persyaratan berhasil diperbarui.',
        ]);
    }

    public function gantiTemplate(GantiTemplateRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $this->detailPersyaratanService->gantiTemplate( $validated['detail_id'], $request->file('berkas'));

        return response()->json([
            'success' => true,
            'message' => 'Template berhasil diganti.',
        ]);
    }

    public function history(string $pelayanan, string $persyaratan): JsonResponse
    {
        $data = $this->detailPersyaratanService->history(id_decode($pelayanan), id_decode($persyaratan));

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}