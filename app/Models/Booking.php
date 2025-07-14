<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'spa_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'service_type',
        'service_price',
        'admin_fee',
        'total_amount',
        'booking_date',
        'booking_time',
        'service_address',
        'notes',
        'status',
        'payment_status',
        'midtrans_transaction_id',
        'payment_method',
        'paid_at'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'paid_at' => 'datetime',
        'service_price' => 'integer',
        'admin_fee' => 'integer',
        'total_amount' => 'integer',
    ];

    /**
     * Get the spa that owns the booking.
     */
    public function spa(): BelongsTo
    {
        return $this->belongsTo(Spa::class, 'spa_id', 'id_spa');
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    /**
     * Get the formatted service price.
     */
    public function getFormattedServicePriceAttribute(): string
    {
        return 'Rp ' . number_format($this->service_price, 0, ',', '.');
    }

    /**
     * Get the formatted admin fee.
     */
    public function getFormattedAdminFeeAttribute(): string
    {
        return 'Rp ' . number_format($this->admin_fee, 0, ',', '.');
    }

    /**
     * Scope for paid bookings.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope for pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope for failed bookings.
     */
    public function scopeFailed($query)
    {
        return $query->where('payment_status', 'failed');
    }

    /**
     * Scope for confirmed bookings.
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope for cancelled bookings.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
}
