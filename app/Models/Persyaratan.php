<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persyaratan extends Model
{
    use HasFactory, EncryptableIdTrait, SoftDeletes;

    protected $table = 'persyaratan'; 

    protected $fillable = [
        'nama_persyaratan',
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

    public function detail_persyaratan()
    {
        return $this->hasMany(Detail_persyaratan::class);
    }
}
