<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'bkg_id',
        'customer_id',
        'room_id',
        'room_type_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_count',
        'check_in_date',
        'check_out_date',
        'total_amount',
        'paid_amount',
        'payment_status',
        'booking_status',
        'special_requests',
        'booking_source',
        'fileupload',
        'notes',

        // Legacy fields for backward compatibility
        'name',
        'room_type',
        'total_numbers',
        'date',
        'time',
        'arrival_date',
        'depature_date',
        'email',
        'ph_number',
        'message',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'guest_count' => 'integer',
        'arrival_date' => 'date',
        'depature_date' => 'date',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    // Accessors
    public function getNightsAttribute()
    {
        if ($this->check_in_date && $this->check_out_date) {
            return $this->check_in_date->diffInDays($this->check_out_date);
        }

        // Fallback for legacy data
        if ($this->arrival_date && $this->depature_date) {
            return Carbon::parse($this->arrival_date)->diffInDays(Carbon::parse($this->depature_date));
        }

        return 1;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge badge-warning',
            'confirmed' => 'badge badge-info',
            'checked_in' => 'badge badge-success',
            'checked_out' => 'badge badge-secondary',
            'cancelled' => 'badge badge-danger',
            'no_show' => 'badge badge-dark',
        ];

        return $badges[$this->booking_status ?? 'pending'] ?? 'badge badge-secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge badge-warning',
            'partial' => 'badge badge-info',
            'paid' => 'badge badge-success',
            'refunded' => 'badge badge-secondary',
        ];

        return $badges[$this->payment_status ?? 'pending'] ?? 'badge badge-secondary';
    }

    // Calculate total amount based on room rate and nights
    public function calculateTotalAmount()
    {
        if ($this->room && $this->nights > 0) {
            return $this->room->rent * $this->nights;
        }
        return 0;
    }

    // Check if booking can be cancelled
    public function canBeCancelled()
    {
        return in_array($this->booking_status ?? 'pending', ['pending', 'confirmed']) &&
            $this->check_in_date > now();
    }

    // Check if guest can check in
    public function canCheckIn()
    {
        return $this->booking_status === 'confirmed' &&
            $this->check_in_date <= now() &&
            $this->check_out_date > now();
    }

    // Check if guest can check out
    public function canCheckOut()
    {
        return $this->booking_status === 'checked_in';
    }

    // Scopes
    public function scopeToday($query)
    {
        return $query->whereDate('check_in_date', today());
    }

    public function scopeCheckingOutToday($query)
    {
        return $query->whereDate('check_out_date', today());
    }

    public function scopeActive($query)
    {
        return $query->whereIn('booking_status', ['confirmed', 'checked_in']);
    }
}
