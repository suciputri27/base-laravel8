<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berkas_post extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $table = 'berkas_posts';

    protected $fillable = [
        'posts_id',
        'berkas'
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
