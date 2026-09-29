<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Detail_persyaratan extends Model
{
    use HasFactory;
    protected $table = 'detail_persyaratan';

    protected $fillable = [
        'persyaratan_id',
        'pelayanan_id',
        'berkas',
    ];

    public function persyaratan()
    {
        return $this->belongsTo(F_Persyaratan::class, 'persyaratan_id');
    }

    public function pelayanan()
    {
        return $this->belongsTo(F_Pelayanan::class, 'pelayanan_id');
    }
}
