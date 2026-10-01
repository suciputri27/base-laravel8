<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\SettingService;

class F_ProfilController extends Controller
{
    protected $settingservice;

    public function __construct(SettingService $settingservice)
    {
        $this->settingservice = $settingservice;
    }

    public function tentang()
    {
        $profil = $this->settingservice->getSetting();
        return view('frontend.tentang', compact('profil'));
    }

    public function visi_misi()
    {
        $profil = $this->settingservice->getSetting();
        return view('frontend.visi-misi', compact('profil'));
    }

    public function motto()
    {
        $profil = $this->settingservice->getSetting();
        return view('frontend.motto', compact('profil'));
    }

    public function tugas_fungsi()
    {
        $profil = $this->settingservice->getSetting();
        return view('frontend.tugas-fungsi', compact('profil'));
    }

    public function sejarah()
    {
        $profil = $this->settingservice->getSetting();
        return view('frontend.sejarah', compact('profil'));
    }
}
