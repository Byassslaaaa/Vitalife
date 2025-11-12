<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gym extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_gym';

    protected $fillable = [
        'nama',
        'alamat',
        'services',
        'fasilitas',
        'description',
        'contact_person',
        'contact_phone',
        'image',
        'maps',
        'is_open'
    ];

    protected $casts = [
        'services' => 'array',
        'fasilitas' => 'array',
        'is_open' => 'boolean',
    ];

    /**
     * Relationship with GymDetail model
     */
    public function gymDetail()
    {
        return $this->hasOne(GymDetail::class, 'gym_id', 'id_gym');
    }

    /**
     * Relationship with GymService model
     */
    public function gymServices()
    {
        return $this->hasMany(GymService::class, 'gym_id', 'id_gym');
    }

    /**
     * Relationship with GymBooking model
     */
    public function gymBookings()
    {
        return $this->hasMany(GymBooking::class, 'gym_id', 'id_gym');
    }

    /**
     * Get the testimonials for this gym
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'testimonial_id', 'id_gym')
                    ->where('testimonial_type', 'gym');
    }

    /**
     * Get approved testimonials only
     */
    public function approvedTestimonials()
    {
        return $this->testimonials()->where('is_approved', true);
    }

    /**
     * Get average rating from approved testimonials
     */
    public function getAverageRatingAttribute()
    {
        $avg = $this->approvedTestimonials()->avg('rating');
        return $avg ? round($avg, 1) : 0;
    }

    /**
     * Get total count of approved testimonials
     */
    public function getTestimonialsCountAttribute()
    {
        return $this->approvedTestimonials()->count();
    }

    // Accessor untuk mendapatkan 3 services pertama
    public function getTopServicesAttribute()
    {
        if (!$this->services || !is_array($this->services)) {
            return [];
        }
        return array_slice($this->services, 0, 3);
    }

    // Accessor untuk status buka/tutup
    public function getOpenStatusAttribute()
    {
        return $this->is_open ? 'Open' : 'Closed';
    }

    // Accessor untuk status dengan waktu
    public function getOpenStatusWithTimeAttribute()
    {
        if ($this->is_open) {
            return 'Open 24 Hours';
        } else {
            return 'Currently Closed';
        }
    }

    /**
     * Get all services (original + additional from detail)
     */
    public function getAllServicesAttribute()
    {
        $allServices = [];

        // Add main services (from gym table)
        if ($this->services && is_array($this->services)) {
            $allServices = array_merge($allServices, $this->services);
        }

        // Add additional services (from gym_details table)
        if ($this->gymDetail && $this->gymDetail->additional_services) {
            $additionalServices = $this->gymDetail->additional_services;
            foreach ($additionalServices as $service) {
                if (isset($service['name']) && isset($service['description'])) {
                    $allServices[] = [
                        'name' => $service['name'],
                        'description' => $service['description'],
                        'image' => $service['image'] ?? null
                    ];
                }
            }
        }

        return $allServices;
    }
}
