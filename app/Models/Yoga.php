<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yoga extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_yoga';

    protected $fillable = [
        'nama',
        'harga',
        'alamat',
        'noHP',
        'waktuBuka',
        'image',
        'maps',
        'class_type',
        'is_open',
        'services'
    ];

    protected $casts = [
        'waktuBuka' => 'array',
        'harga' => 'integer',
        'is_open' => 'boolean',
        'services' => 'array'
    ];

    /**
     * Get the detail configuration for the yoga.
     */
    public function detailConfig()
    {
        return $this->hasOne(YogaDetailConfig::class, 'yoga_id', 'id_yoga');
    }

    /**
     * Get the bookings for the yoga.
     */
    public function bookings()
    {
        return $this->hasMany(YogaBooking::class, 'yoga_id', 'id_yoga');
    }

    /**
     * Get the services for the yoga.
     */
    public function yogaServices()
    {
        return $this->hasMany(YogaService::class, 'yoga_id', 'id_yoga');
    }

    /**
     * Get the testimonials for this yoga
     */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class, 'testimonial_id', 'id_yoga')
                    ->where('testimonial_type', 'yoga');
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

    /**
     * Scope to filter by location
     */
    public function scopeByLocation($query, $location)
    {
        return $query->where('alamat', 'like', '%' . $location . '%');
    }

    /**
     * Scope to filter by price range
     */
    public function scopeByPriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice) {
            $query->where('harga', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('harga', '<=', $maxPrice);
        }
        return $query;
    }

    /**
     * Scope to filter by class type
     */
    public function scopeByClassType($query, $classType)
    {
        return $query->where('class_type', $classType);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    /**
     * Get the hero title for detail page
     */
    public function getHeroTitleAttribute()
    {
        return $this->detailConfig?->hero_title ?? $this->nama;
    }

    /**
     * Get the hero subtitle for detail page
     */
    public function getHeroSubtitleAttribute()
    {
        return $this->detailConfig?->hero_subtitle ?? 'Find your inner peace and strength';
    }

    /**
     * Get gallery images for detail page
     */
    public function getGalleryImagesAttribute()
    {
        $configImages = $this->detailConfig?->gallery_images ?? [];

        // If no custom gallery images, return the main image repeated
        if (empty($configImages)) {
            return array_fill(0, 4, $this->image);
        }

        return $configImages;
    }

    /**
     * Get facilities for detail page
     */
    public function getFacilitiesAttribute()
    {
        return $this->detailConfig?->facilities ?? [
            'Air Conditioned Studio',
            'Yoga Props Available',
            'Meditation Corner',
            'Herbal Tea Corner',
            'Changing Room',
            'Free Parking'
        ];
    }

    /**
     * Get available class types
     */
    public static function getClassTypes()
    {
        return [
            'offline' => 'Offline Class',
            'online' => 'Online Class',
            'private' => 'Private Session',
            'group' => 'Group Class'
        ];
    }
}
