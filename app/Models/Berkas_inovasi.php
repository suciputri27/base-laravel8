<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berkas_inovasi extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'berkas_inovasi';

    protected $fillable = [
        'inovasi_id',
        'berkas'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];

    public function inovasi()
    {
        return $this->belongsTo(Inovasi::class);
    }
}
