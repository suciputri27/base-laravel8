<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publikasi extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'publikasi'; 

    protected $fillable = [
        'judul',
        'jenis_dokumen',
        'deskripsi',
        'is_active',
        'berkas',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
