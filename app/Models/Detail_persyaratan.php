<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_persyaratan extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $fillable = [
        'pelayanan_id',
        'persyaratan_id',
        'berkas'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
