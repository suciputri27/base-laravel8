<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Berkasinovasi extends Model
{
    use HasFactory;
    protected $table = 'berkas_inovasi';

    protected $primaryKey = 'id';

    public function inovasi()
    {
        return $this->belongsTo(F_Inovasi::class, 'inovasi_id', 'id');
    }
}
