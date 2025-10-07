<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'room_id',
        'room_code',
        'check_in',
        'check_out',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
