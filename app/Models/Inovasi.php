<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Inovasi extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'inovasi';

    protected $fillable = [
        'judul',
        'jenis',
        'deskripsi',
        'is_active',
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

    public function berkas()
    {
        return $this->hasMany(Berkas_inovasi::class, 'inovasi_id');
    }
}
