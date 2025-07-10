<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YogaDetailConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'yoga_id',
        'hero_title',
        'hero_subtitle',
        'gallery_images',
        'facilities',
        'booking_policy_title',
        'booking_policy_subtitle',
        'show_opening_hours',
        'show_location_map',
        'custom_css',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'facilities' => 'array',
        'show_opening_hours' => 'boolean',
        'show_location_map' => 'boolean',
    ];

    public function yoga()
    {
        return $this->belongsTo(Yoga::class, 'yoga_id', 'id_yoga');
    }
}
