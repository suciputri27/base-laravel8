<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Publikasi extends Model
{
    use HasFactory;
    protected $table = 'publikasi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'berkas',
    ];
}
