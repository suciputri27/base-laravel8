<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Informasi extends Model
{
    use HasFactory;
    protected $table = 'posts';
    protected $primaryKey = 'id';
    public function kategori()
    {
        return $this->belongsTo(F_Kategori::class, 'category_id', 'id');
    }
}
