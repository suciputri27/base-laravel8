<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\frontend\F_Pelayanan;
use App\Models\frontend\F_Persyaratan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class F_PersyaratanController extends Controller
{
    public function index()
    {
        $pelayanans = F_Pelayanan::with('persyaratans')->get();
        return view('frontend.persyaratan', compact('pelayanans'));
    }

    public function formulir()
    {

        return view('frontend.formulir');
    }

    public function formulir_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = F_Persyaratan::where('cekdokumen', 1)
            ->with('detailPersyaratans');

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $formulir = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $formulir->items(),
            'current_page' => $formulir->currentPage(),
            'last_page'    => $formulir->lastPage(),
            'total'        => $formulir->total(),
        ]);
    }
}
