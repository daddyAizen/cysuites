<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'room_id',
        'room_code',
        'checked_in_at',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
