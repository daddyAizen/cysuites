<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_id',
        'table_id',
        'reservation_date',
        'reservation_time',
        'status',
    ];

    public function guest()
    {
        return $this->belongsTo(Guest::class); 
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }
}
