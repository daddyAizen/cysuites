<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = [
        'name',
        'description',
        'price',
        'picture',
        'is_out_of_stock',
    ];

    protected $appends = ['picture_url']; 

    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
}

