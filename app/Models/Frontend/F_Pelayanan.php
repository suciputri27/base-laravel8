<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Pelayanan extends Model
{
    use HasFactory;
    protected $table = 'pelayanan';

    protected $fillable = [
        'nama_pelayanan',
        'deskripsi',
    ];
    public function persyaratans()
    {
        return $this->belongsToMany(
            F_Persyaratan::class,
            'detail_persyaratan',
            'pelayanan_id',
            'persyaratan_id',
        )->withPivot([
            'berkas',
            'is_active'
        ]);
    }
}
