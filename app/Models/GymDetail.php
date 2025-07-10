<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymDetail extends Model
{
    use HasFactory;

    protected $table = 'gym_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'gym_id',
        'gallery_images',
        'contact_person_name',
        'contact_person_phone',
        'location_maps',
        'additional_services',
        'about_gym',
        'opening_hours',
        'facilities'
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'additional_services' => 'array',
        'opening_hours' => 'array',
        'facilities' => 'array',
    ];

    /**
     * Relationship with Gym model
     */
    public function gym()
    {
        return $this->belongsTo(Gym::class, 'gym_id', 'id_gym');
    }

    /**
     * Get formatted opening hours
     */
    public function getFormattedOpeningHoursAttribute()
    {
        if (!$this->opening_hours) {
            return [];
        }

        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $formatted = [];

        foreach ($days as $day) {
            $formatted[] = [
                'day' => $day,
                'hours' => $this->opening_hours[$day] ?? 'Tutup'
            ];
        }

        return $formatted;
    }

    /**
     * Get gallery images with fallback
     */
    public function getGalleryImagesWithFallbackAttribute()
    {
        $images = $this->gallery_images ?? [];
        
        // If less than 5 images, fill with gym main image or placeholder
        while (count($images) < 5) {
            $images[] = $this->gym->image ?? '/placeholder.svg?height=400&width=600';
        }

        return array_slice($images, 0, 5);
    }

    /**
     * Get additional services with image fallback
     */
    public function getAdditionalServicesWithFallbackAttribute()
    {
        $services = $this->additional_services ?? [];
        
        foreach ($services as &$service) {
            if (!isset($service['image']) || empty($service['image'])) {
                $service['image'] = '/placeholder.svg?height=64&width=64';
            }
        }
        
        return $services;
    }
}
