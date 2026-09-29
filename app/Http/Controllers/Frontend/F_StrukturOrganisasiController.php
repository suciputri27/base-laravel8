<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class F_StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $strukturOrganisasi = \App\Models\Frontend\F_StrukturOrganisasi::select('id', 'berkas')->get();
        return view('frontend.struktur-organisasi', compact('strukturOrganisasi'));
    }
}
