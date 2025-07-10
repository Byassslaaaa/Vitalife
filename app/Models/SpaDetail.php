<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpaDetail extends Model
{
    use HasFactory;

    protected $table = 'spa_details';

    protected $fillable = [
        'spa_id',
        'hero_title',
        'hero_subtitle',
        'description',
        'about_spa',
        'gallery_images',
        'facilities',
        'additional_services',
        'show_facilities',
        'show_opening_hours',
        'show_booking_policy',
        'show_location_map',
        'booking_policy_title',
        'booking_policy_subtitle',
        'contact_person_name',
        'contact_person_phone',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'facilities' => 'array',
        'additional_services' => 'array',
        'show_facilities' => 'boolean',
        'show_opening_hours' => 'boolean',
        'show_booking_policy' => 'boolean',
        'show_location_map' => 'boolean',
    ];

    /**
     * Get the spa that owns the detail configuration
     */
    public function spa()
    {
        return $this->belongsTo(Spa::class, 'spa_id', 'id_spa');
    }
}
