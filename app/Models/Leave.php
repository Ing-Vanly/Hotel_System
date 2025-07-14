<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'reason',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge badge-warning',
            'approved' => 'badge badge-success',
            'rejected' => 'badge badge-danger',
            'cancelled' => 'badge badge-danger', // Support old status value
        ];

        return $badges[$this->status] ?? 'badge badge-secondary';
    }
}
