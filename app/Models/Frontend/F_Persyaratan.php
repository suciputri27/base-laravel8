<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Persyaratan extends Model
{
    use HasFactory;
    protected $table = 'persyaratan';

    protected $fillable = [
        'nama_persyaratan',
    ];

    public function pelayanans()
    {
        return $this->belongsToMany(
            F_Pelayanan::class,
            'detail_persyaratan',
            'pelayanan_id',
            'persyaratan_id'
        )->withPivot([
            'berkas',
            'is_active'
        ]);
    }

    public function detailPersyaratans()
    {
        return $this->hasMany(F_Detail_persyaratan::class, 'persyaratan_id');
    }
}
