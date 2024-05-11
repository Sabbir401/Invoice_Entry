<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date',
        'delivery_date',
        'day',
        'delivery_number',
        'first_name',
        'last_name',
        'telephone_number',
        'address',
        'zip_code',
        'would',
        'floor_number',
        'items',
        'other'
    ];
}
