<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_persyaratan extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'detail_persyaratan';
    
    protected $fillable = [
        'pelayanan_id',
        'persyaratan_id',
        'berkas',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pelayanan()
    {
        return $this->belongsTo(Pelayanan::class);
    }

    public function persyaratan()
    {
        return $this->belongsTo(Persyaratan::class);
    }
}
