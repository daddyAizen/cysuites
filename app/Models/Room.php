<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['name', 'room_code', 'is_booked'];

    public function guests()
{
    return $this->hasMany(Guest::class);
}

}
