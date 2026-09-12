<?php

namespace App\Models;

use App\Traits\EncryptableIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, EncryptableIdTrait;

    protected $fillable = [
        'name',
        'icon',
        'route_or_url',
        'permission_name',
        'order_no',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'order_no' => 'integer',
        'parent_id' => 'integer',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'id',
    ];

    protected $appends = [
        'encrypted_id',
    ];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order_no');
    }
}
