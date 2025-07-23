<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_name',
        'description',
        'base_price',
        'max_occupancy',
        'amenities',
        'status',
    ];

    protected $casts = [
        'amenities' => 'array',
    ];
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
