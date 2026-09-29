<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Profil extends Model
{
    use HasFactory;
    protected $table = 'profile_dinas';

    protected $fillable = [
        'no_telepon',
        'email',
        'tentang',
    ];
}
