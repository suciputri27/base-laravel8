<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\StrukturOrganisasiService;

class F_StrukturOrganisasiController extends Controller
{
    protected $strukturOrganisasiService;

    public function __construct(StrukturOrganisasiService $strukturOrganisasiService)
    {
        $this->strukturOrganisasiService = $strukturOrganisasiService;
    }

    public function index()
    {
        $strukturOrganisasi = $this->strukturOrganisasiService->all();
        return view('frontend.struktur-organisasi', compact('strukturOrganisasi'));
    }
}
