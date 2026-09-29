<?php

namespace App\Models\Frontend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class F_Kategori extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $primaryKey = 'id';

    public function beritas()
    {
        return $this->hasMany(F_Informasi::class, 'category_id', 'id');
    }
}
