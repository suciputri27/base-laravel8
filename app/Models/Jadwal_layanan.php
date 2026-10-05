<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal_layanan extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'jadwal_layanan';

    protected $fillable = [
        'jenis',
        'day',
        'tanggal',
        'open',
        'close',
        'tempat',
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
