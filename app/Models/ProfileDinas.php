<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileDinas extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $fillable = [
        'nama_website',
        'tentang',
        'email',
        'alamat',
        'no_telepon',
        'no_whatsapp',
        'twitter',
        'facebook',
        'instagram',
        'tiktok',
        'youtube',
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
}
