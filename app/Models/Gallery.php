<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory,EncryptableIdTrait; 

    protected $fillable = [
        'judul',
        'type',
        'berkas'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
