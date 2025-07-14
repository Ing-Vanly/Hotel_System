<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'gender',
        'dob',
        'phone',
        'email',
        'national_id',
        'address',
        'position',
        'joining_date',
        'salary',
        'photo',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'joining_date' => 'date',
        'salary' => 'decimal:2',
    ];

    /**
     * Get all leaves for this employee
     */
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
