<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Struktur_organisasi extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $fillable = [
        'berkas',
        'status'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];

}
