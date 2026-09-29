<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\F_Publikasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class F_PublikasiController extends Controller
{
    /**
     * Tampilkan halaman publikasi. Kontennya (card, search, pagination)
     * dirender via JS lewat endpoint data() di bawah, jadi di sini
     * tidak perlu kirim data apa pun ke view.
     */
    public function index()
    {
        return view('frontend.publikasi');
    }

    /**
     * Endpoint AJAX: search + pagination tanpa reload halaman.
     * Dipanggil dari JS pakai fetch(), contoh:
     *   /publikasi/data?search=kata&page=2&per_page=6
     */
    public function data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = F_Publikasi::query();
        $query->where('jenis_dokumen', 5);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $publikasi = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $publikasi->items(),
            'current_page' => $publikasi->currentPage(),
            'last_page'    => $publikasi->lastPage(),
            'total'        => $publikasi->total(),
        ]);
    }

    public function maklumat()
    {
        $profil = F_Publikasi::get();
        $profil->where('jenis_dokumen', 1);
        return view('frontend.maklumat', compact('profil'));
    }

    public function standar_pelayanan()
    {
        return view('frontend.standar-pelayanan');
    }

    public function standar_pelayanan_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = F_Publikasi::query();
        $query->where('jenis_dokumen', 2);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $publikasi = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $publikasi->items(),
            'current_page' => $publikasi->currentPage(),
            'last_page'    => $publikasi->lastPage(),
            'total'        => $publikasi->total(),
        ]);
    }

    public function sop()
    {
        return view('frontend.sop');
    }

    public function sop_data(Request $request): JsonResponse
    {
        $perPage = (int) $request->input('per_page', 6);
        $search  = trim((string) $request->input('search', ''));

        $query = F_Publikasi::query();
        $query->where('jenis_dokumen', 3);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $publikasi = $query->latest()->paginate($perPage);

        return response()->json([
            'success'      => true,
            'data'         => $publikasi->items(),
            'current_page' => $publikasi->currentPage(),
            'last_page'    => $publikasi->lastPage(),
            'total'        => $publikasi->total(),
        ]);
    }

    public function alur_pengaduan()
    {
        $profil = F_Publikasi::where('jenis_dokumen', 4)->get();
        return view('frontend.alur-pengaduan', compact('profil'));
    }
}
