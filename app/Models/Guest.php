<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Guest extends Authenticatable
{
    use HasFactory;
    use Notifiable;
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

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
