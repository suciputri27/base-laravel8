<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelayanan extends Model
{
    use HasFactory, EncryptableIdTrait, SoftDeletes;

    protected $table = 'pelayanan'; 

    protected $fillable = [
        'nama_pelayanan',
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

    public function detail_pesyaratan()
    {
        return $this->hasMany(Detail_persyaratan::class);
    }
}
