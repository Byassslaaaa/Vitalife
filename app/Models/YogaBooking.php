<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YogaBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'yoga_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'booking_date',
        'booking_time',
        'class_type',
        'total_amount',
        'status',
        'payment_status',
        'payment_token',
        'payment_details'
    ];

    public function yoga()
    {
        return $this->belongsTo(Yoga::class, 'yoga_id', 'id_yoga');
    }
}