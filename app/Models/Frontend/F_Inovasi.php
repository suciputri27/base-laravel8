<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Inovasi extends Model
{
    use HasFactory;

    protected $table = 'inovasi';
    protected $primaryKey = 'id';
    public function berkas()
    {
        return $this->hasMany(F_Berkasinovasi::class, 'category_id', 'id');
    }
}
