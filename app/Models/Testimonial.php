<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\GymBooking;
use App\Models\SpaBooking;
use App\Models\YogaBooking;

class Testimonial extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'booking_type',
        'service_completed',
        'testimonial_type',
        'testimonial_id',
        'name',
        'rating',
        'comment',
        'service',
        'is_approved'
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'service_completed' => 'boolean',
        'rating' => 'integer',
    ];

    /**
     * Get the user that created the testimonial
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the testimonial-able model (Spa, Gym, or Yoga)
     */
    public function testimonialable()
    {
        return $this->morphTo(__FUNCTION__, 'testimonial_type', 'testimonial_id');
    }

    /**
     * Get the gym booking if booking_type is 'gym'
     */
    public function gymBooking(): BelongsTo
    {
        return $this->belongsTo(GymBooking::class, 'booking_id');
    }

    /**
     * Get the spa booking if booking_type is 'spa'
     */
    public function spaBooking(): BelongsTo
    {
        return $this->belongsTo(SpaBooking::class, 'booking_id');
    }

    /**
     * Get the yoga booking if booking_type is 'yoga'
     */
    public function yogaBooking(): BelongsTo
    {
        return $this->belongsTo(YogaBooking::class, 'booking_id');
    }

    /**
     * Get the associated booking (polymorphic-like relationship)
     */
    public function booking()
    {
        return match($this->booking_type) {
            'gym' => $this->gymBooking(),
            'spa' => $this->spaBooking(),
            'yoga' => $this->yogaBooking(),
            default => null,
        };
    }

    /**
     * Scope to get only approved testimonials
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope to get testimonials for a specific type and id
     */
    public function scopeForTestimonialable($query, string $type, int $id)
    {
        return $query->where('testimonial_type', $type)
                    ->where('testimonial_id', $id);
    }

    /**
     * Get testimonials for Spa
     */
    public function scopeForSpa($query, int $spaId)
    {
        return $query->forTestimonialable('spa', $spaId);
    }

    /**
     * Get testimonials for Gym
     */
    public function scopeForGym($query, int $gymId)
    {
        return $query->forTestimonialable('gym', $gymId);
    }

    /**
     * Get testimonials for Yoga
     */
    public function scopeForYoga($query, int $yogaId)
    {
        return $query->forTestimonialable('yoga', $yogaId);
    }
}
