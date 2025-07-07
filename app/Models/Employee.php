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
}
