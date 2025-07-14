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
        'class_type'
    ];

    protected $casts = [
        'waktuBuka' => 'array',
        'harga' => 'integer'
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
            [
                'title' => 'Hatha Yoga',
                'description' => 'Gentle yoga practice focusing on basic postures and breathing',
                'icon' => 'fa-solid fa-person-walking'
            ],
            [
                'title' => 'Meditation',
                'description' => 'Mindfulness and meditation sessions for inner peace',
                'icon' => 'fa-solid fa-brain'
            ],
            [
                'title' => 'Breathing Exercises',
                'description' => 'Pranayama techniques for better health and wellness',
                'icon' => 'fa-solid fa-wind'
            ]
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
