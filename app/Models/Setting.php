<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'profile_dinas';

    protected $fillable = [
        'nama_website',
        'tentang',
        'visi',
        'misi',
        'motto',
        'tupoksi',
        'sejarah',
        'email',
        'alamat',
        'no_telepon',
        'no_whatsapp',
        'twitter',
        'facebook',
        'youtube',
        'tiktok',
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];
}
