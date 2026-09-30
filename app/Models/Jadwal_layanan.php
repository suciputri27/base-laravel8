<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal_layanan extends Model
{
    use HasFactory;

    protected $table = 'jadwal_layanan';

    protected $fillable = [
        'jenis',
        'day',
        'open',
        'close',
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
