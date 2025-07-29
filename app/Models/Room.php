<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'bkg_room_id',
        'name',
        'room_type',
        'ac_non_ac',
        'food',
        'bed_count',
        'charges_for_cancellation',
        'rent',
        'phone_number',
        'fileupload',
        'message',
        // New fields will be added after migration
        'room_number',
        'room_type_id',
        'status',
        'floor_number',
        'max_occupancy',
        'is_active',
    ];

    protected $casts = [
        'rent' => 'decimal:2',
        'charges_for_cancellation' => 'decimal:2',
        'bed_count' => 'integer',
        'max_occupancy' => 'integer',
        'floor_number' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationship to room type
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id');
    }

    // Relationship to bookings
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Current active booking
    public function currentBooking()
    {
        return $this->hasOne(Booking::class)
            ->whereIn('booking_status', ['confirmed', 'checked_in'])
            ->where('check_in_date', '<=', now())
            ->where('check_out_date', '>=', now());
    }

    // Check if room is available for given dates
    public function isAvailableForDates($checkIn, $checkOut)
    {
        if ($this->status && $this->status !== 'available') {
            return false;
        }

        return !$this->bookings()
            ->whereIn('booking_status', ['confirmed', 'checked_in'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in_date', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out_date', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in_date', '<=', $checkIn)
                            ->where('check_out_date', '>=', $checkOut);
                    });
            })
            ->exists();
    }

    // Get status badge class
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'available' => 'badge badge-success',
            'occupied' => 'badge badge-danger',
            'maintenance' => 'badge badge-warning',
            'dirty' => 'badge badge-info',
            'out_of_order' => 'badge badge-dark',
        ];

        return $badges[$this->status ?? 'available'] ?? 'badge badge-secondary';
    }

    // Get room display name
    public function getDisplayNameAttribute()
    {
        return $this->room_number ? "Room {$this->room_number}" : $this->name;
    }
}
