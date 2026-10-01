<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\PelayananService;
use App\Services\PersyaratanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class F_PersyaratanController extends Controller
{
    protected $pelayananservice;
    protected $persyaratanservice;

    public function __construct(PelayananService $pelayananservice, PersyaratanService $persyaratanservice)
    {
        $this->pelayananservice = $pelayananservice;
        $this->persyaratanservice = $persyaratanservice;
    }

    // public function index()
    // {
    //     $pelayanans = $this->pelayananservice->getAll();
    //     return view('frontend.persyaratan', compact('pelayanans'));
    // }

    public function formulir()
    {

        return view('frontend.formulir');
    }

    public function formulir_data(Request $request): JsonResponse
    {
        return $this->persyaratanservice->formulir_data($request);
    }
}
