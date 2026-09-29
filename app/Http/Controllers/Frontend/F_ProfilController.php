<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Frontend\F_Profil;
use Illuminate\Http\Request;

class F_ProfilController extends Controller
{
    public function tentang()
    {
        $profil = F_Profil::get();
        return view('frontend.tentang', compact('profil'));
    }

    public function visi_misi()
    {
        $profil = F_Profil::get();
        return view('frontend.visi-misi', compact('profil'));
    }

    public function motto()
    {
        $profil = F_Profil::get();
        return view('frontend.motto', compact('profil'));
    }

    public function tugas_fungsi()
    {
        $profil = F_Profil::get();
        return view('frontend.tugas-fungsi', compact('profil'));
    }

    public function sejarah()
    {
        $profil = F_Profil::get();
        return view('frontend.sejarah', compact('profil'));
    }
}
