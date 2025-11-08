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
        'service_id',
        'service_name',
        'service_price',
        'customer_name',
        'customer_email',
        'customer_phone',
        'booking_date',
        'booking_time',
        'class_type',
        'total_amount',
        'notes',
        'status',
        'payment_status',
        'payment_token',
        'payment_details'
    ];

    protected $casts = [
        'service_price' => 'decimal:2',
        'booking_date' => 'date',
        'booking_time' => 'datetime',
    ];

    /**
     * Relationship with Yoga model
     */
    public function yoga()
    {
        return $this->belongsTo(Yoga::class, 'yoga_id', 'id_yoga');
    }

    /**
     * Relationship with YogaService model
     */
    public function service()
    {
        return $this->belongsTo(YogaService::class, 'service_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->service_price, 0, ',', '.');
    }
}
