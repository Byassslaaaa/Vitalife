<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'gym_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_id',
        'service_name',
        'service_price',
        'status',
        'payment_status',
        'payment_token',
        'booking_date',
        'notes'
    ];

    protected $casts = [
        'service_price' => 'decimal:2',
        'booking_date' => 'datetime',
    ];

    /**
     * Relationship with Gym model
     */
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id_gym');
    }

    /**
     * Relationship with GymService model
     */
    public function service()
    {
        return $this->belongsTo(GymService::class, 'service_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->service_price, 0, ',', '.');
    }
}
