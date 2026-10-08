<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\InovasiService;
use Illuminate\View\View;

class F_InovasiController extends Controller
{
    protected $inovasiservice;
    protected const SILETON_URL = 'https://sileton.agamkab.go.id';

    public function __construct(InovasiService $inovasiservice)
    {
        $this->inovasiservice = $inovasiservice;
    }

    public function index()
    {
        $inovasis = $this->inovasiservice->getInovasi();
        return view('frontend.inovasi', compact('inovasis'));
    }

    public function jemput_bola()
    {
        $layanans = $this->inovasiservice->getJemputBola();
        $siletonUrl = self::SILETON_URL;
        return view('frontend.jemput-bola', compact('layanans', 'siletonUrl'));
    }

    public function show(string $slug): View
    {
        $inovasi = $this->inovasiservice->getBySlug($slug);
        return view('frontend.detail-inovasi', compact('inovasi'));
    }
}
