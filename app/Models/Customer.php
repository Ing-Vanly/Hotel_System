<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'bkg_customer_id',
        'name',
        'email',
        'ph_number',
        'dob',
        'gender',
        'national_id',
        'address',
        'country',
        'fileupload',
        'message',
        'status',
    ];
}
