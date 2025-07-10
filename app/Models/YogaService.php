<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YogaBookingService extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'service_id',
        'service_name',
        'price'
    ];

    /**
     * Get the booking that owns this service.
     */
    public function booking()
    {
        return $this->belongsTo(YogaBooking::class, 'booking_id');
    }
}