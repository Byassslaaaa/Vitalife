<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'spa_id',
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
        'booking_time',
        'therapist_preference',
        'notes'
    ];

    protected $casts = [
        'service_price' => 'decimal:2',
        'booking_date' => 'datetime',
        'booking_time' => 'datetime',
    ];

    /**
     * Relationship with Spa model
     */
    public function spa()
    {
        return $this->belongsTo(Spa::class, 'spa_id', 'id_spa');
    }

    /**
     * Relationship with SpaService model
     */
    public function service()
    {
        return $this->belongsTo(SpaService::class, 'service_id');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->service_price, 0, ',', '.');
    }

    /**
     * Get booking status options
     */
    public static function getStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
    }

    /**
     * Get payment status options
     */
    public static function getPaymentStatusOptions()
    {
        return [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed'
        ];
    }

    /**
     * Scope for pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Get formatted booking datetime
     */
    public function getFormattedBookingDateTimeAttribute()
    {
        if ($this->booking_date && $this->booking_time) {
            return $this->booking_date->format('d/m/Y') . ' - ' . $this->booking_time->format('H:i');
        }
        return 'Belum ditentukan';
    }
}
