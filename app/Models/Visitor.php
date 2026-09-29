<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip_address',
        'session_id',
        'url',
        'user_agent',
        'visited_date',
    ];

    protected $casts = [
        'visited_date' => 'date',
    ];
}
