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
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'max_occupancy' => 'integer',
        'amenities' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationship to rooms
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    // Get available rooms of this type for given dates
    public function availableRoomsForDates($checkIn, $checkOut)
    {
        return $this->rooms()
                    ->where('status', 'available')
                    ->where('is_active', true)
                    ->whereDoesntHave('bookings', function($query) use ($checkIn, $checkOut) {
                        $query->whereIn('status', ['confirmed', 'checked_in'])
                              ->where(function($q) use ($checkIn, $checkOut) {
                                  $q->whereBetween('check_in_date', [$checkIn, $checkOut])
                                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                                    ->orWhere(function($sub) use ($checkIn, $checkOut) {
                                        $sub->where('check_in_date', '<=', $checkIn)
                                            ->where('check_out_date', '>=', $checkOut);
                                    });
                              });
                    });
    }
}