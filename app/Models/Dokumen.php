<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $fillable = [
        'jenis_dokumen_id',
        'nama_dokumen',
        'deskripsi',
        'berkas'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
